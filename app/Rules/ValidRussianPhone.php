<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRussianPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // nullable поля обрабатываются отдельно
        }

        // Убираем все символы кроме цифр и плюса
        $phone = preg_replace('/[^0-9+]/', '', $value);

        // Российские номера могут начинаться с:
        // +7, 8, 7 и содержать 10 цифр после кода страны
        
        // Приводим к единому формату +7XXXXXXXXXX
        if (preg_match('/^\+7([0-9]{10})$/', $phone, $matches)) {
            // Формат +7XXXXXXXXXX - OK
            $localNumber = $matches[1];
        } elseif (preg_match('/^8([0-9]{10})$/', $phone, $matches)) {
            // Формат 8XXXXXXXXXX - OK
            $localNumber = $matches[1];
        } elseif (preg_match('/^7([0-9]{10})$/', $phone, $matches)) {
            // Формат 7XXXXXXXXXX - OK
            $localNumber = $matches[1];
        } else {
            $fail('Неверный формат российского телефона. Ожидается: +7 (XXX) XXX-XX-XX или 8 (XXX) XXX-XX-XX.');
            return;
        }

        // Проверяем код оператора (первые 3 цифры)
        $operatorCode = intval(substr($localNumber, 0, 3));
        
        // Мобильные коды: 900-999
        // Городские коды: начинаются с 3, 4, 8 (301-999, кроме 900-999 - это мобильные)
        $isMobile = ($operatorCode >= 900 && $operatorCode <= 999);
        $isCity = (($operatorCode >= 301 && $operatorCode <= 899) || 
                   ($operatorCode >= 301 && $operatorCode <= 499) ||
                   ($operatorCode >= 800 && $operatorCode <= 899));

        if (!$isMobile && !$isCity) {
            $fail('Неверный код оператора российского телефона.');
        }
    }
}
