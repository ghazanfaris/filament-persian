<?php

namespace Sghazanfari\FilamentPersian\Tables\Filters;

use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Sghazanfari\FilamentPersian\Forms\Components\JalaliDatePicker;

class JalaliDateFilter extends Filter
{
    protected ?string $columnName = null;
    protected bool $withTime = false;

    /**
     * نام ستون در دیتابیس.
     */
    public function column(string $column): static
    {
        $this->columnName = $column;
        return $this;
    }

    /**
     * اگر فیلتر روی یک ستون datetime (با ساعت) است.
     */
    public function withTime(bool $condition = true): static
    {
        $this->withTime = $condition;
        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $from = JalaliDatePicker::make('from')
            ->label('از تاریخ');

        $until = JalaliDatePicker::make('until')
            ->label('تا تاریخ');

        if ($this->withTime) {
            $from->withTime();
            $until->withTime();
        }

        $this->form([$from, $until]);

        $this->query(function (Builder $query, array $data) {
            $column = $this->columnName ?? $this->getName();

            // JalaliDatePicker خودش قبلاً جلالی را به میلادی تبدیل کرده
            if (! empty($data['from'])) {
                $query->where($column, '>=', $data['from']);
            }

            if (! empty($data['until'])) {
                $query->where($column, '<=', $data['until']);
            }
        });
    }
}