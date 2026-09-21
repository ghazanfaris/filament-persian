<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\TextInput;
use Sghazanfari\FilamentPersian\Settings\FontManager;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class PersianNumberInput extends TextInput
{
    protected bool $isMoney = false;
    protected ?string $currency = null;
    protected int $decimals = 0;

    public function money(?string $currency = null): static
    {
        $this->isMoney = true;
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

        $this
            ->inputMode('numeric')
            ->rule(function () {
                return function (string $attribute, $value, $fail) {
                    if ($value === null || $value === '') {
                        return;
                    }

                    $clean = app(JalaliConverter::class)->toLatinDigits((string) $value);

                    // حذف همه جداکننده‌های ممکن (فارسی و انگلیسی)
                    $separators = [
                        FontManager::thousandSeparator(),
                        ',', ' ', '٬', "\u{066C}", "\u{00A0}",
                    ];

                    $clean = str_replace($separators, '', $clean);

                    if (! is_numeric($clean)) {
                        $fail('مقدار وارد شده باید عدد باشد.');
                    }
                };
            })
            ->afterStateHydrated(function (PersianNumberInput $component, $state) {
                if ($state === null || $state === '') {
                    $component->state(null);
                    return;
                }

                $clean = app(JalaliConverter::class)->toLatinDigits((string) $state);

                $separators = [
                    FontManager::thousandSeparator(),
                    ',', ' ', '٬', "\u{066C}", "\u{00A0}",
                ];

                $clean = str_replace($separators, '', $clean);

                $component->state($clean);
            })
            ->dehydrateStateUsing(function ($state) {
                if (blank($state)) {
                    return null;
                }

                $clean = app(JalaliConverter::class)->toLatinDigits((string) $state);

                $separators = [
                    FontManager::thousandSeparator(),
                    ',', ' ', '٬', "\u{066C}", "\u{00A0}",
                ];

                $clean = str_replace($separators, '', $clean);

                if ($clean === '') {
                    return null;
                }

                return $this->decimals > 0
                    ? (float) $clean
                    : (int) $clean;
            })
            ->extraInputAttributes([
                // مقدار اولیه را با کاما نمایش بده
                'x-init' => "
                    if (\$el.value) {
                        let v = \$el.value.replace(/[^0-9.]/g, '');
                        if (v) {
                            let num = parseFloat(v);
                            if (!isNaN(num)) {
                                \$el.value = num.toLocaleString('en-US', {maximumFractionDigits: 2});
                            }
                        }
                    }
                ",
                // با هر ورودی، ارقام را فقط عدد نگه دار و فرمت کن
                'x-on:input' => "
                    let v = \$event.target.value
                        .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
                        .replace(/[^0-9.]/g, '');
                    let parts = v.split('.');
                    if (parts.length > 2) parts = [parts[0], parts.slice(1).join('')];
                    let intPart = parts[0];
                    let decPart = parts[1] || '';
                    if (intPart) intPart = Number(intPart).toLocaleString('en-US');
                    v = decPart ? intPart + '.' + decPart : intPart;
                    \$event.target.value = v;
                ",
                'dir' => 'ltr',
                'style' => 'text-align: left; font-family: monospace;',
            ]);

        // اگر مبلغ است، نماد واحد پول اضافه کن
        if ($this->isMoney) {
            $currency = $this->currency ?? FontManager::currency();

            $symbol = config("filament-persian.numbers.currency.symbols.{$currency}", $currency);

            if ($symbol && $currency !== 'none') {
                $this->suffix($symbol);
            }
        }
    }
}