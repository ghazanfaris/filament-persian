<?php

namespace Sghazanfari\FilamentPersian\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

abstract class PersianStatsWidget extends BaseWidget
{
    // =========================================================
    // متدهای کمکی برای ساخت Stat با فرمت فارسی
    // =========================================================

    /**
     * Stat عددی با فرمت فارسی.
     */
    protected function makeNumberStat(
        string $label,
        int|float $value,
        ?string $description = null,
        ?string $icon = null,
        ?string $color = null,
        ?array $chart = null,
        bool $thousands = true,
    ): Stat {
        $formatted = $thousands
            ? $this->formatNumber($value)
            : $this->formatDigits($value);

        $stat = Stat::make($label, $formatted);

        if ($description) {
            $stat->description($this->formatDigits($description));
        }

        if ($icon) {
            $stat->descriptionIcon($icon);
        }

        if ($color) {
            $stat->color($color);
        }

        if ($chart) {
            $stat->chart($chart);
        }

        return $stat;
    }

    /**
     * Stat مبلغ با فرمت پول فارسی.
     */
    protected function makeMoneyStat(
        string $label,
        int|float $amount,
        ?string $currency = 'IRT',
        ?string $description = null,
        ?string $icon = null,
        ?string $color = null,
        int $decimals = 0,
    ): Stat {
        $formatted = $this->formatMoney($amount, $currency, $decimals);
        $stat = Stat::make($label, $formatted);

        if ($description) {
            $stat->description($this->formatDigits($description));
        }

        if ($icon) {
            $stat->descriptionIcon($icon);
        }

        if ($color) {
            $stat->color($color);
        }

        return $stat;
    }

    /**
     * Stat درصد.
     */
    protected function makePercentStat(
        string $label,
        int|float $percent,
        ?string $description = null,
        ?string $icon = null,
        ?string $color = null,
        int $decimals = 1,
    ): Stat {
        $formatted = $this->formatNumber($percent, $decimals) . '٪';
        $stat = Stat::make($label, $formatted);

        if ($description) {
            $stat->description($this->formatDigits($description));
        }

        if ($icon) {
            $stat->descriptionIcon($icon);
        }

        if ($color) {
            $stat->color($color);
        }

        return $stat;
    }

    /**
     * Stat تاریخ جلالی.
     */
    protected function makeDateStat(
        string $label,
        \DateTimeInterface|string $date,
        string $pattern = 'Y/m/d',
        ?string $description = null,
        ?string $icon = null,
        ?string $color = null,
    ): Stat {
        $formatted = jalali_format($date, $pattern);
        $stat = Stat::make($label, $formatted);

        if ($description) {
            $stat->description($this->formatDigits($description));
        }

        if ($icon) {
            $stat->descriptionIcon($icon);
        }

        if ($color) {
            $stat->color($color);
        }

        return $stat;
    }

    /**
     * Stat «چند وقت پیش».
     */
    protected function makeHumanDateStat(
        string $label,
        \DateTimeInterface|string $date,
        ?string $description = null,
        ?string $icon = null,
        ?string $color = null,
    ): Stat {
        $conv = app(JalaliConverter::class);
        $formatted = $conv->diffForHumans($date);
        $stat = Stat::make($label, $formatted);

        if ($description) {
            $stat->description($this->formatDigits($description));
        }

        if ($icon) {
            $stat->descriptionIcon($icon);
        }

        if ($color) {
            $stat->color($color);
        }

        return $stat;
    }

    /**
     * Stat ساده متنی (فقط اعداد فارسی‌سازی می‌شود).
     */
    protected function makeTextStat(
        string $label,
        string $value,
        ?string $description = null,
        ?string $icon = null,
        ?string $color = null,
    ): Stat {
        $stat = Stat::make($label, $this->formatDigits($value));

        if ($description) {
            $stat->description($this->formatDigits($description));
        }

        if ($icon) {
            $stat->descriptionIcon($icon);
        }

        if ($color) {
            $stat->color($color);
        }

        return $stat;
    }

    // =========================================================
    // فرمت‌کننده‌ها
    // =========================================================

    protected function formatDigits(string|int|float $value): string
    {
        return app(JalaliConverter::class)->toPersianDigits($value);
    }

    protected function formatNumber(int|float $value, int $decimals = 0, bool $thousands = true): string
    {
        $formatted = $thousands
            ? number_format($value, $decimals, '٫', '٬')
            : number_format($value, $decimals, '٫', '');

        return $this->formatDigits($formatted);
    }

    protected function formatMoney(int|float $amount, ?string $currency = 'IRT', int $decimals = 0): string
    {
        $formatted = $this->formatNumber($amount, $decimals);

        $symbol = config("filament-persian.numbers.currency.symbols.{$currency}", $currency);

        return $symbol ? "{$formatted} {$symbol}" : $formatted;
    }

    // =========================================================
    // ابزارهای مقایسه (برای description)
    // =========================================================

    /**
     * توضیح رشد/کاهش به فارسی.
     */
    protected function growthDescription(int|float $current, int|float $previous, string $unit = 'ماه گذشته'): string
    {
        if ($previous == 0) {
            return 'بدون داده قبلی';
        }

        $change = (($current - $previous) / $previous) * 100;
        $change = round(abs($change), 1);

        $direction = $current >= $previous ? 'رشد' : 'کاهش';

        return $this->formatNumber($change, 1) . '٪ ' . $direction . ' نسبت به ' . $unit;
    }

    /**
     * رنگ بر اساس رشد/کاهش.
     */
    protected function growthColor(int|float $current, int|float $previous): string
    {
        return $current >= $previous ? 'success' : 'danger';
    }

    /**
     * آیکون بر اساس رشد/کاهش.
     */
    protected function growthIcon(int|float $current, int|float $previous): string
    {
        return $current >= $previous
            ? 'heroicon-m-arrow-trending-up'
            : 'heroicon-m-arrow-trending-down';
    }
}