<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRussianPassport implements ValidationRule
{
    private $type; // 'series' или 'number'

    public function __construct($type = 'number')
    {
        $this->type = $type;
    }

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

        if ($this->type === 'series') {
            // Серия паспорта РФ: 4 цифры (первые 2 - код региона 01-99)
            if (!preg_match('/^[0-9]{4}$/', $value)) {
                $fail('Серия паспорта должна содержать 4 цифры.');
                return;
            }

            $region = intval(substr($value, 0, 2));
            if ($region < 1 || $region > 99) {
                $fail('Неверный код региона в серии паспорта (должен быть от 01 до 99).');
            }
        } elseif ($this->type === 'number') {
            // Номер паспорта РФ: 6 цифр
            if (!preg_match('/^[0-9]{6}$/', $value)) {
                $fail('Номер паспорта должен содержать 6 цифр.');
            }
        }
    }
}
