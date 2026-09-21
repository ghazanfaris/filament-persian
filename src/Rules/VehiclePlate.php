<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class VehiclePlate implements ValidationRule
{
    /**
     * حروف مجاز در پلاک خودرو ایران.
     * (با هم‌ارز عربی/فارسی برای نرمال‌سازی)
     */
    public const ALLOWED_LETTERS = [
        'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'د', 'ز',
        'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ',
        'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و',
        'ه', 'ی',
    ];

    /**
     * نگاشت حروف عربی به فارسی.
     */
    protected const ARABIC_TO_PERSIAN = [
        'ي' => 'ی',
        'ك' => 'ک',
        'أ' => 'ا',
        'إ' => 'ا',
        'آ' => 'ا',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $plate = app(JalaliConverter::class)->toLatinDigits((string) $value);
        $plate = trim($plate);

        if ($plate === '') {
            return;
        }

        // نرمال‌سازی حروف
        $plate = str_replace(
            array_keys(self::ARABIC_TO_PERSIAN),
            array_values(self::ARABIC_TO_PERSIAN),
            $plate
        );

        // حذف فاصله‌ها و خط تیره برای پارس
        $clean = preg_replace('/[\s\-]+/u', '', $plate);

        // الگوی ۱: 2 رقم + 1 حرف + 3 رقم  (مثل 12ب345)
        if (preg_match('/^(\d{2})([\p{L}]+)(\d{3})$/u', $clean, $m)) {
            $letter = $m[2];
            if (! in_array($letter, self::ALLOWED_LETTERS, true)) {
                $fail('حرف وارد شده در پلاک مجاز نیست.');
                return;
            }
            return;
        }

        // الگوی ۲: با کد ایران در انتها
        // مثل 12ب345ایران67 یا 12ب34567
        if (preg_match('/^(\d{2})([\p{L}]+)(\d{3})(?:ایران)?(\d{2})$/u', $clean, $m)) {
            $letter = $m[2];
            if (! in_array($letter, self::ALLOWED_LETTERS, true)) {
                $fail('حرف وارد شده در پلاک مجاز نیست.');
                return;
            }

            $iranCode = (int) $m[4];
            if ($iranCode < 1 || $iranCode > 99) {
                $fail('کد ایران باید بین ۱ تا ۹۹ باشد.');
                return;
            }
            return;
        }

        // الگوی ۳: فرمت قدیمی 5 رقمی (2 رقم + حرف + 5 رقم)
        if (preg_match('/^(\d{2})([\p{L}]+)(\d{5})$/u', $clean, $m)) {
            $letter = $m[2];
            if (! in_array($letter, self::ALLOWED_LETTERS, true)) {
                $fail('حرف وارد شده در پلاک مجاز نیست.');
                return;
            }
            return;
        }

        // الگوی ۴: پلاک‌های خاص مثل ارتش، سپاه، دولتی
        // مثل: 12ب345-21  یا  ایران 12
        if (preg_match('/^(\d{2})([\p{L}]+)(\d{3})[\-]?(\d{2})$/u', $clean, $m)) {
            $letter = $m[2];
            if (! in_array($letter, self::ALLOWED_LETTERS, true)) {
                $fail('حرف وارد شده در پلاک مجاز نیست.');
                return;
            }
            return;
        }

        $fail('فرمت پلاک خودرو معتبر نیست. مثال صحیح: ۱۲ب۳۴۵ یا ۱۲ب۳۴۵ایران۶۷');
    }

    /**
     * نرمال‌سازی پلاک (حذف فاصله، تبدیل حروف عربی، ارقام فارسی).
     */
    public static function normalize(string $plate): string
    {
        $plate = app(JalaliConverter::class)->toLatinDigits($plate);
        $plate = str_replace(
            array_keys(self::ARABIC_TO_PERSIAN),
            array_values(self::ARABIC_TO_PERSIAN),
            $plate
        );

        return preg_replace('/[\s\-]+/u', '', trim($plate));
    }

    /**
     * فرمت‌دهی زیبا به پلاک.
     */
    public static function format(string $plate): string
    {
        $plate = self::normalize($plate);

        if (preg_match('/^(\d{2})([\p{L}]+)(\d{3})(\d{2})?$/u', $plate, $m)) {
            $base = $m[1] . $m[2] . $m[3];
            if (! empty($m[4])) {
                return $base . ' ایران ' . $m[4];
            }
            return $base;
        }

        return $plate;
    }
}