<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRussianINN implements ValidationRule
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

        // Убираем пробелы
        $inn = preg_replace('/\s+/', '', $value);

        // ИНН может быть 10 цифр (юр. лицо) или 12 цифр (физ. лицо)
        if (!preg_match('/^[0-9]{10}$|^[0-9]{12}$/', $inn)) {
            $fail('ИНН должен содержать 10 цифр (для юридических лиц) или 12 цифр (для физических лиц).');
            return;
        }

        $length = strlen($inn);

        // Проверка контрольной суммы для ИНН из 10 цифр (юр. лицо)
        if ($length === 10) {
            $coefficients = [2, 4, 10, 3, 5, 9, 4, 6, 8];
            $sum = 0;
            for ($i = 0; $i < 9; $i++) {
                $sum += intval($inn[$i]) * $coefficients[$i];
            }
            $checksum = ($sum % 11) % 10;

            if (intval($inn[9]) !== $checksum) {
                $fail('Неверная контрольная сумма ИНН.');
            }
        }

        // Проверка контрольных сумм для ИНН из 12 цифр (физ. лицо)
        if ($length === 12) {
            // Первая контрольная сумма (11-я цифра)
            $coefficients1 = [7, 2, 4, 10, 3, 5, 9, 4, 6, 8];
            $sum1 = 0;
            for ($i = 0; $i < 10; $i++) {
                $sum1 += intval($inn[$i]) * $coefficients1[$i];
            }
            $checksum1 = ($sum1 % 11) % 10;

            if (intval($inn[10]) !== $checksum1) {
                $fail('Неверная контрольная сумма ИНН.');
                return;
            }

            // Вторая контрольная сумма (12-я цифра)
            $coefficients2 = [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8];
            $sum2 = 0;
            for ($i = 0; $i < 11; $i++) {
                $sum2 += intval($inn[$i]) * $coefficients2[$i];
            }
            $checksum2 = ($sum2 % 11) % 10;

            if (intval($inn[11]) !== $checksum2) {
                $fail('Неверная контрольная сумма ИНН.');
            }
        }
    }
}
