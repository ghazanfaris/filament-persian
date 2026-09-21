<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\TextInput;
use Sghazanfari\FilamentPersian\Rules\BankCardNumber;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class IranianBankCardInput extends TextInput
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->rule(new BankCardNumber())
            ->placeholder('۶۰۳۷-۹۹۷۵-۱۲۳۴-۵۶۷۸')
            ->maxLength(19)  // 16 + 3 خط تیره
            ->inputMode('numeric')
            ->dehydrateStateUsing(function (?string $state): ?string {
                if (blank($state)) return null;
                $clean = app(JalaliConverter::class)->toLatinDigits($state);
                return preg_replace('/[\s\-]/', '', $clean);
            })
            ->extraInputAttributes([
                'x-on:input' => "
                    let v = \$event.target.value
                        .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
                        .replace(/[^0-9]/g, '')
                        .slice(0, 16);
                    let groups = v.match(/.{1,4}/g) || [];
                    \$event.target.value = groups.join('-');
                ",
                'dir' => 'ltr',
                'style' => 'text-align: left; font-family: monospace; letter-spacing: 1px;',
            ]);
    }
}