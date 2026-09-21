<?php

namespace Sghazanfari\FilamentPersian\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Sghazanfari\FilamentPersian\Support\PersianString;

trait HasPersianGlobalSearch
{
    /**
     * جستجوی فارسی با نرمال‌سازی (ی/ک عربی و فارسی).
     *
     * در v5 این متد باید void برنگرداند و query را in-place تغییر دهد.
     * Filament اول جستجوی عادی خودش را با where اضافه می‌کند،
     * سپس این متد اجرا می‌شود. ما با orWhere نتیجه نرمال‌شده را
     * به‌عنوان alternative اضافه می‌کنیم.
     */
    public static function modifyGlobalSearchQuery(Builder $query, string $search): void
    {
        $string = app(PersianString::class);
        $normalized = $string->normalizeForSearch($search);

        if ($normalized === '') {
            return;
        }

        $attributes = static::getGloballySearchableAttributes();
        $model = $query->getModel();

        // جستجوی نرمال‌شده را به‌عنوان یک گروه orWhere اضافه کن
        $query->orWhere(function (Builder $q) use ($attributes, $normalized, $string, $model) {
            foreach ($attributes as $attribute) {
                // روابط را فعلاً پشتیبانی نمی‌کنیم
                if (str_contains($attribute, '.')) {
                    continue;
                }

                $column = $model->qualifyColumn($attribute);
                $normalizedColumn = $string->sqlNormalize($column);

                $q->orWhereRaw(
                    "{$normalizedColumn} LIKE ?",
                    ['%' . $normalized . '%']
                );
            }
        });
    }
}