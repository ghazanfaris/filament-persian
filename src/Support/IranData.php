<?php

namespace Sghazanfari\FilamentPersian\Support;

class IranData
{
    /**
     * کش داخلی برای جلوگیری از خواندن مکرر فایل.
     */
    protected static array $cache = [];

    /**
     * همه استان‌های ایران.
     *
     * @return array<int, array{name:string, slug:string, capital:string}>
     */
    public static function provinces(): array
    {
        if (! isset(self::$cache['provinces'])) {
            self::$cache['provinces'] = self::load('provinces');
        }

        return self::$cache['provinces'];
    }

    /**
     * یک استان با شناسه.
     */
    public static function province(int $id): ?array
    {
        return self::provinces()[$id] ?? null;
    }

    /**
     * پیدا کردن استان با اسلاگ.
     */
    public static function provinceBySlug(string $slug): ?array
    {
        foreach (self::provinces() as $id => $province) {
            if ($province['slug'] === $slug) {
                return array_merge(['id' => $id], $province);
            }
        }

        return null;
    }

    /**
     * آرایه آماده برای Select: [id => name].
     *
     * @return array<int, string>
     */
    public static function provincesForSelect(): array
    {
        $out = [];
        foreach (self::provinces() as $id => $province) {
            $out[$id] = $province['name'];
        }

        return $out;
    }

        /**
     * @return array<int, array{code:string, name:string, name_en:string, prefixes:array, sheba_code:string}>
     */
    public static function banks(): array
    {
        if (! isset(self::$cache['banks'])) {
            self::$cache['banks'] = self::load('banks');
        }

        return self::$cache['banks'];
    }

    /**
     * پیدا کردن بانک با کد.
     */
    public static function bank(string $code): ?array
    {
        foreach (self::banks() as $bank) {
            if ($bank['code'] === $code) {
                return $bank;
            }
        }

        return null;
    }

    /**
     * پیدا کردن بانک با پیشوند کارت (۶ رقم اول).
     */
    public static function bankByCardPrefix(string $cardNumber): ?array
    {
        $prefix = substr(preg_replace('/\D/', '', $cardNumber), 0, 6);

        if (strlen($prefix) < 6) {
            return null;
        }

        foreach (self::banks() as $bank) {
            if (in_array($prefix, $bank['prefixes'], true)) {
                return $bank;
            }
        }

        return null;
    }

    /**
     * آرایه آماده برای Select: [code => name].
     *
     * @return array<string, string>
     */
    public static function banksForSelect(): array
    {
        $out = [];
        foreach (self::banks() as $bank) {
            $out[$bank['code']] = $bank['name'];
        }

        return $out;
    }

    /**
     * مناطق ۲۲گانه شهرداری تهران.
     *
     * @return array<int, array{name:string, neighborhoods:array}>
     */
    public static function tehranRegions(): array
    {
        if (! isset(self::$cache['tehran_regions'])) {
            self::$cache['tehran_regions'] = self::load('tehran_regions');
        }

        return self::$cache['tehran_regions'];
    }

    public static function tehranRegion(int $id): ?array
    {
        return self::tehranRegions()[$id] ?? null;
    }

    /**
     * @return array<int, string>
     */
    public static function tehranRegionsForSelect(): array
    {
        $out = [];
        foreach (self::tehranRegions() as $id => $region) {
            $out[$id] = $region['name'];
        }

        return $out;
    }

        /**
     * شهرهای یک استان از دیتابیس.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function cities(int $provinceId)
    {
        if (! class_exists(\Sghazanfari\FilamentPersian\Models\IranCity::class)) {
            return collect();
        }

        return \Sghazanfari\FilamentPersian\Models\IranCity::ofProvince($provinceId)
            ->orderBy('name')
            ->get();
    }

    /**
     * آرایه آماده برای Select: [id => name].
     *
     * @return array<int, string>
     */
    public static function citiesForSelect(?int $provinceId = null): array
    {
        if (! class_exists(\Sghazanfari\FilamentPersian\Models\IranCity::class)) {
            return [];
        }

        $query = \Sghazanfari\FilamentPersian\Models\IranCity::query();

        if ($provinceId !== null) {
            $query->ofProvince($provinceId);
        }

        return $query->orderBy('name')->pluck('name', 'id')->all();
    }

    /**
     * پیدا کردن شهر با شناسه.
     */
    public static function city(int $id): ?array
    {
        if (! class_exists(\Sghazanfari\FilamentPersian\Models\IranCity::class)) {
            return null;
        }

        $city = \Sghazanfari\FilamentPersian\Models\IranCity::find($id);

        return $city ? $city->toArray() : null;
    }
    /**
     * فایل داده را از resources/data لود می‌کند.
     */
    protected static function load(string $name): array
    {
        $path = __DIR__ . '/../../resources/data/' . $name . '.php';

        if (! file_exists($path)) {
            return [];
        }

        return require $path;
    }

    /**
     * پاک کردن کش (برای تست).
     */
    public static function clearCache(): void
    {
        self::$cache = [];
    }
}