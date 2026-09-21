<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class Mobile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = app(JalaliConverter::class)->toLatinDigits((string) $value);
        $value = preg_replace('/[\s\-\(\)]/', '', $value);
        $value = preg_replace('/^(\+98|0098|98)/', '0', $value);

        if (! preg_match('/^09\d{9}$/', $value)) {
            $fail('شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.');
        }
    }
}