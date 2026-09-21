<?php

namespace Sghazanfari\FilamentPersian\Commands;

use Illuminate\Console\Command;
use Sghazanfari\FilamentPersian\Models\IranCity;

class SeedCitiesCommand extends Command
{
    protected $signature = 'filament-persian:seed-cities
                            {--fresh : حذف همه شهرهای موجود و درج مجدد}';

    protected $description = 'درج شهرهای ایران در دیتابیس';

    public function handle(): int
    {
        $this->info('🏙️  درج شهرهای ایران...');
        $this->newLine();

        if ($this->option('fresh')) {
            $count = IranCity::count();
            IranCity::truncate();
            $this->warn("حذف {$count} شهر موجود.");
        }

        $dataPath = __DIR__ . '/../../resources/data/cities.php';

        if (! file_exists($dataPath)) {
            $this->error('فایل cities.php پیدا نشد!');
            return self::FAILURE;
        }

        $cities = require $dataPath;
        $inserted = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($this->countTotal($cities));
        $bar->start();

        foreach ($cities as $provinceId => $provinceCities) {
            foreach ($provinceCities as $city) {
                $bar->advance();

                $exists = IranCity::where('province_id', $provinceId)
                    ->where('name', $city['name'])
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                IranCity::create([
                    'province_id' => $provinceId,
                    'name' => $city['name'],
                    'is_capital' => $city['is_capital'] ?? false,
                    'population' => $city['population'] ?? null,
                ]);

                $inserted++;
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ {$inserted} شهر جدید درج شد.");
        if ($skipped > 0) {
            $this->line("   {$skipped} شهر از قبل موجود بود.");
        }

        $this->newLine();
        $this->line('جمع کل: <fg=green>' . IranCity::count() . '</> شهر');

        return self::SUCCESS;
    }

    protected function countTotal(array $cities): int
    {
        $total = 0;
        foreach ($cities as $province) {
            $total += count($province);
        }

        return $total;
    }
}