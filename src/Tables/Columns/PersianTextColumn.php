<?php

namespace Sghazanfari\FilamentPersian\Tables\Columns;

use Filament\Tables\Columns\TextColumn;
use Sghazanfari\FilamentPersian\Settings\FontManager;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class PersianTextColumn extends TextColumn
{
    protected bool $persianDigits = true;
    protected bool $thousandsSeparator = false;

    /**
     * اگر می‌خواهی جداکننده هزارگان داشته باشد (مثل id → ۱٬۲۳۴).
     */
    public function thousands(bool $condition = true): static
    {
        $this->thousandsSeparator = $condition;
        return $this;
    }

    /**
     * غیرفعال کردن تبدیل ارقام فارسی برای این ستون.
     */
    public function withoutPersianDigits(bool $condition = true): static
    {
        $this->persianDigits = ! $condition;
        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(function ($state) {
            if ($state === null || $state === '') {
                return $state;
            }

            $conv = app(JalaliConverter::class);

            if ($this->thousandsSeparator && is_numeric($state)) {
                $num = (float) $state;

                $thousandSeparator = FontManager::thousandSeparator();
                $decimalSeparator = FontManager::decimalSeparator();

                $state = number_format($num, 0, $decimalSeparator, $thousandSeparator);
            }

            // اولویت: تنظیمات سراسری > تنظیمات ستون
            $shouldConvert = $this->persianDigits && FontManager::persianNumbersEnabled();

            return $shouldConvert
                ? $conv->toPersianDigits((string) $state)
                : (string) $state;
        });

        $this->alignRight();
    }
}