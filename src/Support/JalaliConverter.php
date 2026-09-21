<?php

namespace Sghazanfari\FilamentPersian\Support;

use Carbon\Carbon;
use DateTimeInterface;

/**
 * تبدیل دوطرفه میلادی ↔ جلالی + فرمت‌گذاری
 * الگوریتم: Borkowski (jdf) — دقیق و اثبات‌شده
 */
class JalaliConverter
{
    public const PERSIAN_DIGITS = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    public const LATIN_DIGITS   = ['0','1','2','3','4','5','6','7','8','9'];

    /** میلادی → جلالی: [jy, jm, jd] */
    public function toJalali(int $gy, int $gm, int $gd): array
    {
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];

        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666 + (365 * $gy)
              + (int)(($gy2 + 3) / 4)
              - (int)(($gy2 + 99) / 100)
              + (int)(($gy2 + 399) / 400)
              + $gd + $g_d_m[$gm - 1];

        $jy = -1595 + (33 * (int)($days / 12053));
        $days %= 12053;

        $jy += 4 * (int)($days / 1461);
        $days %= 1461;

        if ($days > 365) {
            $jy += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }

        if ($days < 186) {
            $jm = 1 + (int)($days / 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + (int)(($days - 186) / 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }

    /** جلالی → میلادی: [gy, gm, gd] */
    public function toGregorian(int $jy, int $jm, int $jd): array
    {
        $jy += 1595;
        $days = -355668 + (365 * $jy)
              + ((int)($jy / 33) * 8)
              + (int)((($jy % 33) + 3) / 4)
              + $jd
              + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);

        $gy = 400 * (int)($days / 146097);
        $days %= 146097;

        if ($days > 36524) {
            $gy += 100 * (int)(--$days / 36524);
            $days %= 36524;
            if ($days >= 365) $days++;
        }

        $gy += 4 * (int)($days / 1461);
        $days %= 1461;

        if ($days > 365) {
            $gy += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }

        $gd = $days + 1;
        $sal_a = [0, 31, ($gy % 4 == 0 && ($gy % 100 != 0 || $gy % 400 == 0)) ? 29 : 28,
                  31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        for ($gm = 1; $gm <= 12 && $gd > $sal_a[$gm]; $gm++) {
            $gd -= $sal_a[$gm];
        }

        return [$gy, $gm, $gd];
    }

    /** آیا سال جلالی کبیسه است؟ */
    public function isLeapYear(int $jy): bool
    {
        return in_array($jy % 33, [1, 5, 9, 13, 17, 22, 26, 30], true);
    }

    /** تعداد روزهای ماه جلالی */
    public function daysInMonth(int $jy, int $jm): int
    {
        if ($jm <= 6) return 31;
        if ($jm <= 11) return 30;
        return $this->isLeapYear($jy) ? 30 : 29;
    }

    /** Carbon → آرایه جلالی [y, m, d] */
    public function carbonToJalali(DateTimeInterface|string $date): array
    {
        $c = $date instanceof DateTimeInterface ? Carbon::instance($date) : Carbon::parse($date);
        return $this->toJalali($c->year, $c->month, $c->day);
    }

    /** جلالی → Carbon */
    public function jalaliToCarbon(int $jy, int $jm, int $jd, int $h = 0, int $i = 0, int $s = 0): Carbon
    {
        [$gy, $gm, $gd] = $this->toGregorian($jy, $jm, $jd);
        return Carbon::create($gy, $gm, $gd, $h, $i, $s);
    }

    /** ارقام لاتین → فارسی */
    public function toPersianDigits(string|int|float $value): string
    {
        return str_replace(self::LATIN_DIGITS, self::PERSIAN_DIGITS, (string) $value);
    }

    /** ارقام فارسی/عربی → لاتین */
    public function toLatinDigits(string|int|float $value): string
    {
        $value = (string) $value;
        $value = str_replace(self::PERSIAN_DIGITS, self::LATIN_DIGITS, $value);
        return str_replace(
            ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'],
            self::LATIN_DIGITS,
            $value
        );
    }

    /**
     * فرمت کردن تاریخ به شکل جلالی.
     * حروف مجاز در الگو:
     *   Y = ۱۴۰۳  y = ۰۳  m = ۰۵  n = ۵  d = ۱۲  j = ۱۲
     *   H = ۱۴  i = ۳۰  s = ۴۵
     *   F/M = نام ماه    l = نام روز هفته    D = حرف روز هفته
     */
    public function format(DateTimeInterface|string $date, string $pattern = 'Y/m/d'): string
    {
        $c = $date instanceof DateTimeInterface ? Carbon::instance($date) : Carbon::parse($date);
        [$jy, $jm, $jd] = $this->toJalali($c->year, $c->month, $c->day);

        $months = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
        $weekdays = ['یک‌شنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنج‌شنبه','جمعه','شنبه'];
        $weekdaysShort = ['ی','د','س','چ','پ','ج','ش'];

        $replacements = [
            'Y' => (string) $jy,
            'y' => substr((string) $jy, -2),
            'm' => str_pad((string) $jm, 2, '0', STR_PAD_LEFT),
            'n' => (string) $jm,
            'd' => str_pad((string) $jd, 2, '0', STR_PAD_LEFT),
            'j' => (string) $jd,
            'H' => $c->format('H'),
            'i' => $c->format('i'),
            's' => $c->format('s'),
            'F' => $months[$jm - 1],
            'M' => $months[$jm - 1],
            'l' => $weekdays[$c->dayOfWeek],
            'D' => $weekdaysShort[$c->dayOfWeek],
        ];

        $result = '';
        $len = strlen($pattern);
        for ($k = 0; $k < $len; $k++) {
            $ch = $pattern[$k];
            $result .= $replacements[$ch] ?? $ch;
        }

        return $this->toPersianDigits($result);
    }

    /** اختلاف انسانی: «۳ روز پیش» */
    public function diffForHumans(DateTimeInterface|string $date): string
    {
        $c = $date instanceof DateTimeInterface ? Carbon::instance($date) : Carbon::parse($date);
        return $this->toPersianDigits($c->locale('fa')->diffForHumans());
    }
}