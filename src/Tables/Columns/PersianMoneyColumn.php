<?php

namespace Sghazanfari\FilamentPersian\Tables\Columns;

use Filament\Tables\Columns\TextColumn;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class PersianMoneyColumn extends TextColumn
{
    protected ?string $currency = 'IRT';
    protected int $decimals = 0;

    public function currency(?string $currency): static
    {
        $this->currency = $currency;
        return $this;
    }

    public function decimals(int $decimals): static
    {
        $this->decimals = $decimals;
        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(function ($state) {
            if ($state === null || $state === '') {
                return '—';
            }

            $conv = app(JalaliConverter::class);
            $num = (float) $conv->toLatinDigits((string) $state);

            // ← استفاده از تنظیمات
            $thousand = \Sghazanfari\FilamentPersian\Settings\FontManager::thousandSeparator();
            $decimal = \Sghazanfari\FilamentPersian\Settings\FontManager::decimalSeparator();

            $formatted = number_format($num, $this->decimals, $decimal, $thousand);

            if (\Sghazanfari\FilamentPersian\Settings\FontManager::persianNumbersEnabled()) {
                $formatted = $conv->toPersianDigits($formatted);
            }

            $currencyKey = $this->currency ?? \Sghazanfari\FilamentPersian\Settings\FontManager::currency();
            $symbol = config("filament-persian.numbers.currency.symbols.{$currencyKey}", $currencyKey);

            return $symbol ? "{$formatted} {$symbol}" : $formatted;
        });

        $this->alignRight();
    }
}