<?php

namespace Sghazanfari\FilamentPersian\Database\Seeders;

use Illuminate\Database\Seeder;
use Sghazanfari\FilamentPersian\Models\IranCity;

class IranCitiesSeeder extends Seeder
{
    public function run(): void
    {
        $dataPath = __DIR__ . '/../../resources/data/cities.php';

        if (! file_exists($dataPath)) {
            return;
        }

        $cities = require $dataPath;

        foreach ($cities as $provinceId => $provinceCities) {
            foreach ($provinceCities as $city) {
                IranCity::updateOrCreate(
                    [
                        'province_id' => $provinceId,
                        'name' => $city['name'],
                    ],
                    [
                        'is_capital' => $city['is_capital'] ?? false,
                        'population' => $city['population'] ?? null,
                    ]
                );
            }
        }
    }
}