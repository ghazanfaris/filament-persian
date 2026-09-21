<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Carbon\Carbon;
use Filament\Forms\Components\Field;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class JalaliDatePicker extends Field
{
    protected string $view = 'filament-persian::forms.components.jalali-date-picker';

    protected bool $withTime = false;
    protected bool $withSeconds = false;

    public function withTime(bool $condition = true, bool $withSeconds = false): static
    {
        $this->withTime = $condition;
        $this->withSeconds = $withSeconds;
        return $this;
    }

    public function withSeconds(bool $condition = true): static
    {
        $this->withSeconds = $condition;
        if ($condition) {
            $this->withTime = true;
        }
        return $this;
    }

    public function isWithTime(): bool
    {
        return $this->withTime;
    }

    public function isWithSeconds(): bool
    {
        return $this->withSeconds;
    }

    /**
     * قبل از ذخیره، مقدار جلالی را به میلادی تبدیل می‌کند.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function ($state) {
            if (blank($state)) {
                return null;
            }

            // اگر Carbon یا DateTime بود، مستقیم به رشته میلادی
            if ($state instanceof \DateTimeInterface) {
                return Carbon::instance($state)->format('Y-m-d H:i:s');
            }

            // اگر آرایه جلالی بود
            if (is_array($state) && isset($state['y'], $state['m'], $state['d'])) {
                $conv = app(JalaliConverter::class);
                return $conv->jalaliToCarbon(
                    (int) $state['y'], (int) $state['m'], (int) $state['d'],
                    (int) ($state['h'] ?? 0), (int) ($state['i'] ?? 0), (int) ($state['s'] ?? 0)
                )->format('Y-m-d H:i:s');
            }

            // اگر رشته بود
            return static::convertToGregorian((string) $state);
        });
    }

    /**
     * مقدار را برای نمایش در input (جلالی).
     */
    public function getJalaliValue(): ?string
    {
        $state = $this->getState();
        if (blank($state)) {
            return null;
        }

        $conv = app(JalaliConverter::class);
        $pattern = $this->withTime
            ? ($this->withSeconds ? 'Y/m/d H:i:s' : 'Y/m/d H:i')
            : 'Y/m/d';

        if ($state instanceof \DateTimeInterface) {
            return $conv->format($state, $pattern);
        }

        if (is_array($state) && isset($state['y'], $state['m'], $state['d'])) {
            $carbon = $conv->jalaliToCarbon(
                (int) $state['y'], (int) $state['m'], (int) $state['d'],
                (int) ($state['h'] ?? 0), (int) ($state['i'] ?? 0), (int) ($state['s'] ?? 0)
            );
            return $conv->format($carbon, $pattern);
        }

        if (is_string($state)) {
            // تشخیص خودکار: اگر سال بین ۱۳۰۰ تا ۱۵۰۰ است، جلالی است
            $clean = $conv->toLatinDigits($state);
            if (preg_match('#^(\d{4})#', str_replace(['-', '.'], '/', $clean), $m)) {
                $year = (int) $m[1];

                // سال >= 1700 → میلادی → تبدیل به جلالی
                if ($year >= 1700) {
                    try {
                        return $conv->format($clean, $pattern);
                    } catch (\Throwable) {
                        return $state;
                    }
                }

                // سال بین 1300-1500 → از قبل جلالی → همان
                return $state;
            }
        }

        return null;
    }

    /**
     * تبدیل رشته جلالی به میلادی (datetime string).
     */
    public static function convertToGregorian(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $conv = app(JalaliConverter::class);
        $clean = $conv->toLatinDigits($value);
        $clean = str_replace(['-', '.'], '/', $clean);

        if (preg_match('#^(\d{4})/(\d{1,2})/(\d{1,2})(?:\s+(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?)?$#', $clean, $m)) {
            $year = (int) $m[1];

            // اگر سال بزرگ است → میلادی است، همان را برگردان
            if ($year >= 1700) {
                try {
                    return Carbon::parse($clean)->format('Y-m-d H:i:s');
                } catch (\Throwable) {
                    return null;
                }
            }

            // جلالی → میلادی
            return $conv->jalaliToCarbon(
                $year, (int) $m[2], (int) $m[3],
                (int) ($m[4] ?? 0), (int) ($m[5] ?? 0), (int) ($m[6] ?? 0)
            )->format('Y-m-d H:i:s');
        }

        try {
            return Carbon::parse($clean)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return null;
        }
    }
}