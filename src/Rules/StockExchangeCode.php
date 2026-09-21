<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class StockExchangeCode implements ValidationRule
{
    /**
     * کدهای تکراری که نباید قبول شوند.
     */
    protected const BLOCKED_CODES = [
        '00000000000',
        '11111111111',
        '22222222222',
        '33333333333',
        '44444444444',
        '55555555555',
        '66666666666',
        '77777777777',
        '88888888888',
        '99999999999',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $code = app(JalaliConverter::class)->toLatinDigits((string) $value);
        $code = preg_replace('/[\s\-]/', '', $code);

        if (! preg_match('/^\d{11}$/', $code)) {
            $fail('کد بورسی باید ۱۱ رقم باشد.');
            return;
        }

        if (in_array($code, self::BLOCKED_CODES, true)) {
            $fail('کد بورسی وارد شده معتبر نیست.');
            return;
        }

        // کد بورسی معمولاً با 1 شروع می‌شود (اشخاص حقیقی)
        // یا با 5 (اشخاص حقوقی). ولی این قاعده‌ی قطعی نیست،
        // پس فعلاً فقط ساختار را چک می‌کنیم.
    }
}