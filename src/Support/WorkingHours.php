<?php

namespace Sghazanfari\FilamentPersian\Support;

use Carbon\Carbon;

class WorkingHours
{
    /**
     * کش داخلی.
     */
    protected static ?array $config = null;

    /**
     * تنظیمات پیش‌فرض ساعت کاری اداری ایران.
     */
    public static function defaultConfig(): array
    {
        return [
            // شنبه تا چهارشنبه: ۸ تا ۱۶
            6 => ['start' => '08:00', 'end' => '16:00'], // شنبه
            0 => ['start' => '08:00', 'end' => '16:00'], // یک‌شنبه
            1 => ['start' => '08:00', 'end' => '16:00'], // دوشنبه
            2 => ['start' => '08:00', 'end' => '16:00'], // سه‌شنبه
            3 => ['start' => '08:00', 'end' => '16:00'], // چهارشنبه
            4 => ['start' => '08:00', 'end' => '13:00'], // پنج‌شنبه (نیمه‌وقت)
            5 => null,                                    // جمعه (تعطیل)
        ];
    }

    /**
     * تنظیمات ساعت کاری (قابل override).
     */
    public static function config(): array
    {
        if (self::$config !== null) {
            return self::$config;
        }

        $custom = \Sghazanfari\FilamentPersian\Settings\FontManager::get('working_hours');

        return self::$config = is_array($custom) && ! empty($custom)
            ? $custom
            : self::defaultConfig();
    }

    /**
     * تنظیم ساعت کاری سفارشی.
     */
    public static function setConfig(array $config): void
    {
        self::$config = $config;
        \Sghazanfari\FilamentPersian\Settings\FontManager::set('working_hours', $config);
    }

    /**
     * بازگردانی به پیش‌فرض.
     */
    public static function resetConfig(): void
    {
        self::$config = null;
        \Sghazanfari\FilamentPersian\Settings\FontManager::set('working_hours', null);
    }

    /**
     * آیا این روز هفته کاری است؟
     *
     * @param  int  $dayOfWeek  0=یک‌شنبه تا 6=شنبه (استاندارد Carbon)
     */
    public static function isWorkingDay(int $dayOfWeek): bool
    {
        return self::config()[$dayOfWeek] !== null;
    }

    /**
     * ساعت شروع کار در یک روز.
     */
    public static function startTime(int $dayOfWeek): ?string
    {
        $config = self::config()[$dayOfWeek] ?? null;

        return $config['start'] ?? null;
    }

    /**
     * ساعت پایان کار در یک روز.
     */
    public static function endTime(int $dayOfWeek): ?string
    {
        $config = self::config()[$dayOfWeek] ?? null;

        return $config['end'] ?? null;
    }

    /**
     * آیا این لحظه در ساعت کاری است؟
     *
     * @param  Carbon|string|null  $moment
     */
    public static function isWorkingTime(Carbon|string|null $moment = null): bool
    {
        $carbon = $moment === null
            ? Carbon::now()
            : ($moment instanceof Carbon ? $moment : Carbon::parse($moment));

        // روز تعطیل رسمی
        if (IranHolidays::isHoliday($carbon)) {
            return false;
        }

        // روز غیرکاری هفتگی
        if (! self::isWorkingDay($carbon->dayOfWeek)) {
            return false;
        }

        $start = self::startTime($carbon->dayOfWeek);
        $end = self::endTime($carbon->dayOfWeek);

        if ($start === null || $end === null) {
            return false;
        }

        $startTime = $carbon->copy()->setTimeFromTimeString($start);
        $endTime = $carbon->copy()->setTimeFromTimeString($end);

        return $carbon->between($startTime, $endTime);
    }

    /**
     * آیا امروز روز کاری است؟
     */
    public static function isTodayWorkingDay(): bool
    {
        $today = Carbon::today();

        if (IranHolidays::isHoliday($today)) {
            return false;
        }

        return self::isWorkingDay($today->dayOfWeek);
    }

    /**
     * لحظه شروع روز کاری بعدی.
     */
    public static function nextWorkingMoment(Carbon|string|null $from = null): Carbon
    {
        $carbon = $from === null
            ? Carbon::now()
            : ($from instanceof Carbon ? $from->copy() : Carbon::parse($from));

        // اگر همین حالا ساعت کاری است، همین را برگردان
        if (self::isWorkingTime($carbon)) {
            return $carbon;
        }

        // امشب یا امروز به سمت ساعت شروع حرکت کن
        for ($i = 0; $i < 14; $i++) {
            $checkDay = $carbon->copy()->addDays($i);

            if (IranHolidays::isHoliday($checkDay)) {
                continue;
            }

            $start = self::startTime($checkDay->dayOfWeek);
            if ($start === null) {
                continue;
            }

            $startMoment = $checkDay->copy()->setTimeFromTimeString($start);

            if ($startMoment->gt($carbon)) {
                return $startMoment;
            }
        }

        // fallback: فردا صبح ۸ صبح
        return $carbon->copy()->addDay()->setTime(8, 0);
    }

    /**
     * لحظه پایان روز کاری فعلی یا بعدی.
     */
    public static function nextEndMoment(Carbon|string|null $from = null): Carbon
    {
        $carbon = $from === null
            ? Carbon::now()
            : ($from instanceof Carbon ? $from->copy() : Carbon::parse($from));

        $end = self::endTime($carbon->dayOfWeek);

        if ($end !== null && ! IranHolidays::isHoliday($carbon)) {
            $endMoment = $carbon->copy()->setTimeFromTimeString($end);

            if ($endMoment->gt($carbon)) {
                return $endMoment;
            }
        }

        // پایان روز کاری بعدی
        $nextWorking = self::nextWorkingMoment($carbon);

        $nextEnd = self::endTime($nextWorking->dayOfWeek);

        if ($nextEnd !== null) {
            return $nextWorking->copy()->setTimeFromTimeString($nextEnd);
        }

        return $nextWorking->copy()->setTime(16, 0);
    }

    /**
     * ساعت کاری به‌صورت رشته قابل نمایش.
     *
     * @return array<string, string|null>
     */
    public static function weeklySchedule(): array
    {
        $names = [
            6 => 'شنبه',
            0 => 'یک‌شنبه',
            1 => 'دوشنبه',
            2 => 'سه‌شنبه',
            3 => 'چهارشنبه',
            4 => 'پنج‌شنبه',
            5 => 'جمعه',
        ];

        $out = [];

        foreach ($names as $dow => $name) {
            $start = self::startTime($dow);
            $end = self::endTime($dow);

            $out[$name] = ($start !== null && $end !== null)
                ? "{$start} تا {$end}"
                : null;
        }

        return $out;
    }

    /**
     * مجموع ساعت کاری در هفته.
     */
    public static function weeklyHours(): float
    {
        $total = 0.0;

        foreach (self::config() as $schedule) {
            if ($schedule === null) {
                continue;
            }

            $start = Carbon::parse($schedule['start']);
            $end = Carbon::parse($schedule['end']);

            $total += $start->diffInMinutes($end) / 60;
        }

        return round($total, 1);
    }

    /**
     * ساعت کاری باقی‌مانده امروز (به دقیقه).
     */
    public static function remainingMinutesToday(Carbon|string|null $moment = null): int
    {
        $carbon = $moment === null
            ? Carbon::now()
            : ($moment instanceof Carbon ? $moment : Carbon::parse($moment));

        if (IranHolidays::isHoliday($carbon)) {
            return 0;
        }

        $end = self::endTime($carbon->dayOfWeek);
        if ($end === null) {
            return 0;
        }

        $endMoment = $carbon->copy()->setTimeFromTimeString($end);

        if ($endMoment->lte($carbon)) {
            return 0;
        }

        // فقط اگر در ساعت کاری باشیم
        if (! self::isWorkingTime($carbon)) {
            return 0;
        }

        return (int) $carbon->diffInMinutes($endMoment);
    }

    /**
     * پاک کردن کش.
     */
    public static function clearCache(): void
    {
        self::$config = null;
    }
}