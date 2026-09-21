<?php

namespace Sghazanfari\FilamentPersian\Concerns;

use Carbon\Carbon;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

trait HasJalaliDates
{
    /**
     * گرفتن مقدار یک ستون به شکل فرمت‌شده جلالی.
     */
    public function jalali(string $attribute, string $pattern = 'Y/m/d'): ?string
    {
        $value = $this->getAttribute($attribute);
        if (! $value) return null;

        return app(JalaliConverter::class)->format($value, $pattern);
    }

    /**
     * اختلاف انسانی (۳ روز پیش).
     */
    public function jalaliHuman(string $attribute): ?string
    {
        $value = $this->getAttribute($attribute);
        if (! $value) return null;

        return app(JalaliConverter::class)->diffForHumans($value);
    }

    /**
     * Scope برای فیلتر بازه جلالی.
     */
    public function scopeWhereJalaliBetween(
        $query,
        string $column,
        int $jy1, int $jm1, int $jd1,
        int $jy2, int $jm2, int $jd2
    ) {
        $conv = app(JalaliConverter::class);
        $from = $conv->jalaliToCarbon($jy1, $jm1, $jd1)->startOfDay();
        $to   = $conv->jalaliToCarbon($jy2, $jm2, $jd2)->endOfDay();

        return $query->whereBetween($column, [$from, $to]);
    }
}