<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\TextInput;
use Sghazanfari\FilamentPersian\Rules\SocialSecurityNumber;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class IranianSocialSecurityInput extends TextInput
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->rule(new SocialSecurityNumber())
            ->placeholder('۱۰ رقمی')
            ->maxLength(12)  // 10 + 2 خط تیره
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
                        .slice(0, 10);
                    if (v.length > 3 && v.length <= 9) {
                        v = v.slice(0, 3) + '-' + v.slice(3);
                    } else if (v.length > 9) {
                        v = v.slice(0, 3) + '-' + v.slice(3, 9) + '-' + v.slice(9);
                    }
                    \$event.target.value = v;
                ",
                'dir' => 'ltr',
                'style' => 'text-align: left; font-family: monospace; letter-spacing: 1px;',
            ]);
    }
}