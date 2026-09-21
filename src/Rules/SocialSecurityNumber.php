<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class SocialSecurityNumber implements ValidationRule
{
    /**
     * کدهای تکراری که نباید قبول شوند.
     */
    protected const BLOCKED_CODES = [
        '0000000000',
        '1111111111',
        '2222222222',
        '3333333333',
        '4444444444',
        '5555555555',
        '6666666666',
        '7777777777',
        '8888888888',
        '9999999999',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $number = app(JalaliConverter::class)->toLatinDigits((string) $value);
        $number = preg_replace('/[\s\-]/', '', $number);

        if (! preg_match('/^\d{10}$/', $number)) {
            $fail('شماره بیمه تأمین اجتماعی باید ۱۰ رقم باشد.');
            return;
        }

        if (in_array($number, self::BLOCKED_CODES, true)) {
            $fail('شماره بیمه وارد شده معتبر نیست.');
            return;
        }
    }
}