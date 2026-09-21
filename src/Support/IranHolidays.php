<?php

namespace Sghazanfari\FilamentPersian\Support;

use Carbon\Carbon;

class IranHolidays
{
    /**
     * کش داخلی.
     */
    protected static array $cache = [];

    /**
     * روزهای هفته تعطیل (پنجشنبه=4 و جمعه=5 در Carbon).
     */
    public const WEEKEND_DAYS = [5]; // جمعه
    public const HALF_WEEKEND_DAYS = [4]; // پنجشنبه (نیمه‌تعطیل)

    /**
     * تعطیلات شمسی (ثابت هر سال).
     */
    public static function solarHolidays(): array
    {
        if (! isset(self::$cache['solar'])) {
            self::$cache['solar'] = self::load('solar_holidays');
        }

        return self::$cache['solar'];
    }

    /**
     * تعطیلات قمری برای یک سال شمسی.
     */
    public static function lunarHolidays(int $jalaliYear): array
    {
        if (! isset(self::$cache['lunar'])) {
            self::$cache['lunar'] = self::load('lunar_holidays');
        }

        return self::$cache['lunar'][$jalaliYear] ?? [];
    }

    /**
     * آیا تاریخ داده‌شده تعطیل رسمی است؟
     *
     * @param  Carbon|string  $date
     */
    public static function isHoliday(Carbon|string $date): bool
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        $carbon = $carbon->copy()->startOfDay();

        // ۱. جمعه = تعطیل
        if (in_array($carbon->dayOfWeek, self::WEEKEND_DAYS, true)) {
            return true;
        }

        // ۲. تعطیلات شمسی
        [$jy, $jm, $jd] = self::toJalali($carbon);

        foreach (self::solarHolidays() as $holiday) {
            if ($holiday['is_holiday']
                && $holiday['month'] === $jm
                && $holiday['day'] === $jd) {
                return true;
            }
        }

        // ۳. تعطیلات قمری
        $dateString = $carbon->toDateString();

        foreach (self::lunarHolidays($jy) as $holiday) {
            if ($holiday['date'] === $dateString) {
                return true;
            }
        }

        return false;
    }

    /**
     * نام مناسبت‌های آن روز.
     *
     * @return array<string>
     */
    public static function holidayNames(Carbon|string $date): array
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        $carbon = $carbon->copy()->startOfDay();

        $names = [];

        // جمعه
        if ($carbon->dayOfWeek === 5) {
            $names[] = 'جمعه';
        }

        [$jy, $jm, $jd] = self::toJalali($carbon);

        // تعطیلات شمسی
        foreach (self::solarHolidays() as $holiday) {
            if ($holiday['month'] === $jm && $holiday['day'] === $jd) {
                $names[] = $holiday['name'];
            }
        }

        // تعطیلات قمری
        $dateString = $carbon->toDateString();

        foreach (self::lunarHolidays($jy) as $holiday) {
            if ($holiday['date'] === $dateString) {
                $names[] = $holiday['name'];
            }
        }

        return $names;
    }

    /**
     * همه تعطیلات یک سال شمسی.
     *
     * @return array<array{date: string, jalali: string, name: string, type: string}>
     */
    public static function holidaysOfYear(int $jalaliYear): array
    {
        $out = [];

        // شروع سال شمسی
        $converter = app(JalaliConverter::class);
        $start = $converter->jalaliToCarbon($jalaliYear, 1, 1);
        $end = $converter->jalaliToCarbon($jalaliYear, 12, $converter->daysInMonth($jalaliYear, 12));

        // تعطیلات شمسی
        foreach (self::solarHolidays() as $holiday) {
            if (! $holiday['is_holiday']) {
                continue;
            }

            $date = $converter->jalaliToCarbon($jalaliYear, $holiday['month'], $holiday['day']);
            $out[] = [
                'date' => $date->toDateString(),
                'jalali' => $converter->format($date, 'Y/m/d'),
                'name' => $holiday['name'],
                'type' => 'solar',
            ];
        }

        // تعطیلات قمری
        foreach (self::lunarHolidays($jalaliYear) as $holiday) {
            $date = Carbon::parse($holiday['date']);
            $out[] = [
                'date' => $date->toDateString(),
                'jalali' => $converter->format($date, 'Y/m/d'),
                'name' => $holiday['name'],
                'type' => 'lunar',
            ];
        }

        // همه جمعه‌های سال
        $current = $start->copy();
        while ($current->lte($end)) {
            if ($current->dayOfWeek === 5) {
                $out[] = [
                    'date' => $current->toDateString(),
                    'jalali' => $converter->format($current, 'Y/m/d'),
                    'name' => 'جمعه',
                    'type' => 'weekly',
                ];
            }
            $current->addDay();
        }

        // مرتب‌سازی بر اساس تاریخ
        usort($out, fn ($a, $b) => strcmp($a['date'], $b['date']));

        return $out;
    }

    /**
     * تعطیلات بین دو تاریخ.
     *
     * @return array<array{date: string, jalali: string, name: string, type: string}>
     */
    public static function holidaysBetween(Carbon|string $from, Carbon|string $until): array
    {
        $from = $from instanceof Carbon ? $from : Carbon::parse($from);
        $until = $until instanceof Carbon ? $until : Carbon::parse($until);

        $out = [];
        $current = $from->copy()->startOfDay();

        while ($current->lte($until)) {
            $names = self::holidayNames($current);

            foreach ($names as $name) {
                $out[] = [
                    'date' => $current->toDateString(),
                    'name' => $name,
                ];
            }

            $current->addDay();
        }

        return $out;
    }

    /**
     * روز کاری بعدی.
     */
    public static function nextWorkingDay(Carbon|string $date, int $skip = 1): Carbon
    {
        $carbon = $date instanceof Carbon ? $date->copy() : Carbon::parse($date);
        $skipped = 0;

        while ($skipped < $skip) {
            $carbon->addDay();
            if (! self::isHoliday($carbon)) {
                $skipped++;
            }
        }

        return $carbon;
    }

    /**
     * روز کاری قبلی.
     */
    public static function previousWorkingDay(Carbon|string $date, int $skip = 1): Carbon
    {
        $carbon = $date instanceof Carbon ? $date->copy() : Carbon::parse($date);
        $skipped = 0;

        while ($skipped < $skip) {
            $carbon->subDay();
            if (! self::isHoliday($carbon)) {
                $skipped++;
            }
        }

        return $carbon;
    }

    /**
     * تعداد روزهای کاری بین دو تاریخ (شامل هر دو).
     */
    public static function workingDaysBetween(Carbon|string $from, Carbon|string $until): int
    {
        $from = $from instanceof Carbon ? $from->copy()->startOfDay() : Carbon::parse($from)->startOfDay();
        $until = $until instanceof Carbon ? $until->copy()->startOfDay() : Carbon::parse($until)->startOfDay();

        if ($until->lt($from)) {
            return 0;
        }

        $count = 0;
        $current = $from->copy();

        while ($current->lte($until)) {
            if (! self::isHoliday($current)) {
                $count++;
            }
            $current->addDay();
        }

        return $count;
    }

    /**
     * آیا پنجشنبه است (نیمه‌تعطیل)؟
     */
    public static function isThursday(Carbon|string $date): bool
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $carbon->dayOfWeek === 4;
    }

    /**
     * آیا جمعه است؟
     */
    public static function isFriday(Carbon|string $date): bool
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $carbon->dayOfWeek === 5;
    }

    /**
     * آیا پنجشنبه بعد از ساعت ۱۳ است (تعطیل رسمی اداری)؟
     */
    public static function isThursdayAfternoon(Carbon|string $date): bool
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

        return $carbon->dayOfWeek === 4 && $carbon->hour >= 13;
    }

    /**
     * پاک کردن کش.
     */
    public static function clearCache(): void
    {
        self::$cache = [];
    }

    /**
     * تبدیل میلادی به جلالی.
     *
     * @return array{0:int, 1:int, 2:int}
     */
    protected static function toJalali(Carbon $date): array
    {
        return app(JalaliConverter::class)->toJalali(
            $date->year,
            $date->month,
            $date->day
        );
    }

    /**
     * لود فایل داده.
     */
    protected static function load(string $name): array
    {
        $path = __DIR__ . '/../../resources/data/' . $name . '.php';

        if (! file_exists($path)) {
            return [];
        }

        return require $path;
    }
}