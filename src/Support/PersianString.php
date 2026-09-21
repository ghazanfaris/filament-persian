<?php

namespace Sghazanfari\FilamentPersian\Support;

class PersianString
{
    /**
     * جفت‌های نویسه‌های عربی و فارسی.
     */
    public const CHAR_PAIRS = [
        'ي' => 'ی',  // ي عربی → ی فارسی
        'ك' => 'ک',  // ك عربی → ک فارسی
        'ة' => 'ه',  // ة عربی → ه
        'ۀ' => 'ه',  // ۀ → ه
        'ؤ' => 'و',  // ؤ → و
        'إ' => 'ا',  // إ → ا
        'أ' => 'ا',  // أ → ا
        'آ' => 'آ',  // آ (خودش)
        'ﻻ' => 'لا', // لا
        '٠' => '۰',
        '١' => '۱',
        '٢' => '۲',
        '٣' => '۳',
        '٤' => '۴',
        '٥' => '۵',
        '٦' => '۶',
        '٧' => '۷',
        '٨' => '۸',
        '٩' => '۹',
    ];

    /**
     * نرمال‌سازی: ی/ک عربی → فارسی، ارقام عربی → فارسی.
     */
    public function normalize(string $text): string
    {
        $text = str_replace(
            array_keys(self::CHAR_PAIRS),
            array_values(self::CHAR_PAIRS),
            $text
        );

        // حذف نیم‌فاصله‌های تکراری
        $text = preg_replace('/\x{200C}+/u', "\x{200C}", $text);

        return $text;
    }

    /**
     * نرمال‌سازی + حذف فاصله‌ها + lowercase (برای مقایسه).
     */
    public function normalizeForSearch(string $text): string
    {
        $text = $this->normalize($text);
        // حذف نیم‌فاصله (چون کاربر ممکن است نزند)
        $text = str_replace("\x{200C}", '', $text);
        $text = preg_replace('/\s+/u', ' ', $text);
        return trim($text);
    }

    /**
     * جستجوی فارسی (نرمال‌شده).
     */
    public function contains(string $haystack, string $needle): bool
    {
        return str_contains(
            $this->normalizeForSearch($haystack),
            $this->normalizeForSearch($needle)
        );
    }

    /**
     * ساخت SQL برای نرمال‌سازی یک ستون در سمت دیتابیس.
     * از REPLACE های تودرتو استفاده می‌کند.
     */
    public function sqlNormalize(string $column): string
    {
        $sql = $column;

        foreach (self::CHAR_PAIRS as $from => $to) {
            if ($from === $to) continue;
            $sql = "REPLACE({$sql}, '{$from}', '{$to}')";
        }

        // حذف نیم‌فاصله
        $sql = "REPLACE({$sql}, '\u{200C}', '')";

        return $sql;
    }

    /**
     * حروف اول برای Avatar.
     */
    public function initials(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name));
        $first = mb_substr($parts[0] ?? '', 0, 1, 'UTF-8');
        $last = count($parts) > 1 ? mb_substr(end($parts), 0, 1, 'UTF-8') : '';
        return $first . $last;
    }
}