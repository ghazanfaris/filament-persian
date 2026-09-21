<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class EconomicCode implements ValidationRule
{
    /**
     * کدهای اقتصادی معروف که نباید قبول شوند.
     */
    protected const BLOCKED_CODES = [
        '00000000000000',
        '11111111111111',
        '22222222222222',
        '33333333333333',
        '44444444444444',
        '55555555555555',
        '66666666666666',
        '77777777777777',
        '88888888888888',
        '99999999999999',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $code = app(JalaliConverter::class)->toLatinDigits((string) $value);
        $code = preg_replace('/[\s\-]/', '', $code);

        if (! preg_match('/^\d{14}$/', $code)) {
            $fail('شماره اقتصادی باید ۱۴ رقم باشد.');
            return;
        }

        if (in_array($code, self::BLOCKED_CODES, true)) {
            $fail('شماره اقتصادی وارد شده معتبر نیست.');
            return;
        }

        // شماره اقتصادی معمولاً با 411 یا اعداد نزدیک شروع می‌شود،
        // ولی این یک قاعده عمومی نیست. فعلاً فقط ساختار را چک می‌کنیم.
        // برای اعتبارسنجی واقعی، نیاز به API سازمان امور مالیاتی است.
    }
}