<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\TextInput;
use Sghazanfari\FilamentPersian\Rules\StockExchangeCode;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class IranianStockExchangeCodeInput extends TextInput
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->rule(new StockExchangeCode())
            ->placeholder('۱۱ رقمی')
            ->maxLength(11)
            ->inputMode('numeric')
            ->dehydrateStateUsing(function (?string $state): ?string {
                if (blank($state)) return null;
                return app(JalaliConverter::class)->toLatinDigits($state);
            })
            ->extraInputAttributes([
                'x-on:input' => "
                    \$event.target.value = \$event.target.value
                        .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
                        .replace(/[^0-9]/g, '')
                        .slice(0, 11);
                ",
                'dir' => 'ltr',
                'style' => 'text-align: left; font-family: monospace; letter-spacing: 2px;',
            ]);
    }
}