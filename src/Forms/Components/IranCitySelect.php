<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\Select;
use Sghazanfari\FilamentPersian\Models\IranCity;

class IranCitySelect extends Select
{
    protected ?string $provinceField = 'province_id';

    /**
     * نام فیلد استانی که این شهر به آن وابسته است.
     */
    public function provinceField(string $field): static
    {
        $this->provinceField = $field;
        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this
            ->label('شهر')
            ->searchable()
            ->preload()
            ->native(false)
            ->options(function (callable $get) {
                $provinceId = $get($this->provinceField);

                if (! $provinceId) {
                    return [];
                }

                if (! class_exists(IranCity::class)) {
                    return [];
                }

                return IranCity::ofProvince((int) $provinceId)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all();
            })
            ->disabled(fn (callable $get) => ! $get($this->provinceField))
            ->helperText(function (callable $get) {
                return $get($this->provinceField)
                    ? null
                    : 'اول استان را انتخاب کنید.';
            })
            ->live()
            ->afterStateUpdated(function (callable $set, $state) {
                // وقتی استان عوض شد، شهر پاک شود
                // این بخش در خود فیلد استان مدیریت می‌شود
            });
    }
}