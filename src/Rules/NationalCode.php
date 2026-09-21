<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class NationalCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $code = app(JalaliConverter::class)->toLatinDigits((string) $value);

        if (! preg_match('/^\d{10}$/', $code)) {
            $fail('کد ملی باید ۱۰ رقم باشد.');
            return;
        }

        // کدهای تکراری
        if (preg_match('/^(\d)\1{9}$/', $code)) {
            $fail('کد ملی وارد شده معتبر نیست.');
            return;
        }

        // الگوریتم checksum
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $code[$i]) * (10 - $i);
        }
        $r = $sum % 11;
        $check = (int) $code[9];

        $valid = ($r < 2 && $check === $r) || ($r >= 2 && $check === 11 - $r);

        if (! $valid) {
            $fail('کد ملی وارد شده معتبر نیست.');
        }
    }
}