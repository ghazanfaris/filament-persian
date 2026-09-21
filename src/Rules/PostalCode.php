<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class PostalCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $code = app(JalaliConverter::class)->toLatinDigits((string) $value);

        if (! preg_match('/^\d{10}$/', $code)) {
            $fail('کد پستی باید ۱۰ رقم باشد.');
            return;
        }

        if ($code === '0000000000' || $code === '1111111111' || $code === '2222222222') {
            $fail('کد پستی وارد شده معتبر نیست.');
            return;
        }
    }
}