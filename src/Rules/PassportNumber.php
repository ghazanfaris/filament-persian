<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class PassportNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $passport = app(JalaliConverter::class)->toLatinDigits((string) $value);
        $passport = strtoupper(trim(preg_replace('/[\s\-]/', '', $passport)));

        if ($passport === '') {
            return;
        }

        // الگوی پاسپورت ایران: ۱ حرف + ۸ رقم (مثل A12345678)
        // برخی پاسپورت‌های قدیمی: ۹ رقم خالص
        // برخی موارد جدید: حرف + ۷ یا ۸ رقم

        // حالت ۱: حرف + ۸ رقم
        if (preg_match('/^[A-Z]\d{8}$/', $passport)) {
            return;
        }

        // حالت ۲: ۹ رقم خالص (پاسپورت‌های قدیمی)
        if (preg_match('/^\d{9}$/', $passport)) {
            // کدهای تکراری را رد کن
            if (preg_match('/^(\d)\1{8}$/', $passport)) {
                $fail('شماره گذرنامه وارد شده معتبر نیست.');
                return;
            }
            return;
        }

        // حالت ۳: ۲ حرف + ۷ رقم (به‌ندرت)
        if (preg_match('/^[A-Z]{2}\d{7}$/', $passport)) {
            return;
        }

        $fail('شماره گذرنامه معتبر نیست. فرمت‌های صحیح: A12345678 یا 123456789');
    }

    /**
     * نرمال‌سازی (حذف فاصله، بزرگ کردن حروف، تبدیل ارقام فارسی).
     */
    public static function normalize(string $passport): string
    {
        $passport = app(JalaliConverter::class)->toLatinDigits($passport);
        return strtoupper(preg_replace('/[\s\-]/', '', trim($passport)));
    }

    /**
     * فرمت‌دهی زیبا (حرف جدا + فاصله + اعداد).
     */
    public static function format(string $passport): string
    {
        $passport = self::normalize($passport);

        if (preg_match('/^([A-Z])(\d{8})$/', $passport, $m)) {
            return $m[1] . ' - ' . $m[2];
        }

        return $passport;
    }
}