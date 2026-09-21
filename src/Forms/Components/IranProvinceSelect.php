<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\Select;
use Sghazanfari\FilamentPersian\Support\IranData;

class IranProvinceSelect extends Select
{
    /**
     * فیلد شهری که باید هنگام تغییر استان پاک شود.
     */
    protected ?string $resetCityField = null;

    public function resetCityField(?string $field): static
    {
        $this->resetCityField = $field;
        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this
            ->label('استان')
            ->options(fn () => IranData::provincesForSelect())
            ->searchable()
            ->preload()
            ->native(false)
            ->live()
            ->afterStateUpdated(function (callable $set) {
                if ($this->resetCityField) {
                    $set($this->resetCityField, null);
                }
            });
    }
}