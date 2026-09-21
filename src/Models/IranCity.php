<?php

namespace Sghazanfari\FilamentPersian\Models;

use Illuminate\Database\Eloquent\Model;
use Sghazanfari\FilamentPersian\Support\IranData;

class IranCity extends Model
{
    protected $table = 'iran_cities';

    protected $fillable = [
        'province_id',
        'name',
        'name_en',
        'slug',
        'population',
        'is_capital',
    ];

    protected $casts = [
        'is_capital' => 'boolean',
        'population' => 'integer',
    ];

    /**
     * نام استان (از داده‌های ثابت).
     */
    public function getProvinceNameAttribute(): ?string
    {
        return IranData::province($this->province_id)['name'] ?? null;
    }

    /**
     * اسکوپ: شهرهای یک استان.
     */
    public function scopeOfProvince($query, int $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    /**
     * اسکوپ: فقط مراکز استان.
     */
    public function scopeCapitals($query)
    {
        return $query->where('is_capital', true);
    }

    /**
     * اسکوپ: جستجو در نام.
     */
    public function scopeSearch($query, string $term)
    {
        $term = str_replace(['ي', 'ك'], ['ی', 'ک'], $term);

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('name_en', 'like', "%{$term}%");
        });
    }
}