<?php

use Sghazanfari\FilamentPersian\Support\JalaliConverter;

if (! function_exists('jalali')) {
    function jalali(DateTimeInterface|string|null $date = null): array
    {
        return app(JalaliConverter::class)->carbonToJalali($date ?? now());
    }
}

if (! function_exists('jalali_format')) {
    function jalali_format(DateTimeInterface|string|null $date = null, string $pattern = 'Y/m/d'): string
    {
        return app(JalaliConverter::class)->format($date ?? now(), $pattern);
    }
}

if (! function_exists('jalali_now')) {
    function jalali_now(): string
    {
        return jalali_format(now(), 'Y/m/d H:i');
    }
}

if (! function_exists('to_jalali_carbon')) {
    function to_jalali_carbon(int $jy, int $jm, int $jd, int $h = 0, int $i = 0, int $s = 0): \Carbon\Carbon
    {
        return app(JalaliConverter::class)->jalaliToCarbon($jy, $jm, $jd, $h, $i, $s);
    }
}

if (! function_exists('fa_digits')) {
    function fa_digits(string|int|float $value): string
    {
        return app(JalaliConverter::class)->toPersianDigits($value);
    }
}

if (! function_exists('en_digits')) {
    function en_digits(string|int|float $value): string
    {
        return app(JalaliConverter::class)->toLatinDigits($value);
    }
}

if (! function_exists('is_valid_national_code')) {
    function is_valid_national_code(string $code): bool
    {
        $code = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)->toLatinDigits($code);
        if (! preg_match('/^\d{10}$/', $code)) return false;
        if (preg_match('/^(\d)\1{9}$/', $code)) return false;

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $code[$i]) * (10 - $i);
        }
        $r = $sum % 11;
        $check = (int) $code[9];
        return ($r < 2 && $check === $r) || ($r >= 2 && $check === 11 - $r);
    }
}

if (! function_exists('is_valid_mobile')) {
    function is_valid_mobile(string $mobile): bool
    {
        $mobile = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)->toLatinDigits($mobile);
        $mobile = preg_replace('/[\s\-\(\)]/', '', $mobile);
        $mobile = preg_replace('/^(\+98|0098|98)/', '0', $mobile);
        return (bool) preg_match('/^09\d{9}$/', $mobile);
    }
}

if (! function_exists('normalize_mobile')) {
    function normalize_mobile(string $mobile): ?string
    {
        $mobile = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)->toLatinDigits($mobile);
        $mobile = preg_replace('/[\s\-\(\)]/', '', $mobile);
        $mobile = preg_replace('/^(\+98|0098|98)/', '0', $mobile);
        return preg_match('/^09\d{9}$/', $mobile) ? $mobile : null;
    }
}

if (! function_exists('is_valid_sheba')) {
    function is_valid_sheba(string $sheba): bool
    {
        $sheba = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)->toLatinDigits($sheba);
        $sheba = strtoupper(preg_replace('/[\s\-]/', '', $sheba));
        $sheba = preg_replace('/^IR/', '', $sheba);
        if (! preg_match('/^\d{24}$/', $sheba)) return false;

        $rearranged = substr($sheba, 2) . '1827' . substr($sheba, 0, 2);
        $mod = '';
        $len = strlen($rearranged);
        for ($i = 0; $i < $len; $i++) {
            $mod = (int) ($mod . $rearranged[$i]) % 97;
        }
        return $mod === 1;
    }
}

if (! function_exists('normalize_sheba')) {
    function normalize_sheba(string $sheba): ?string
    {
        $sheba = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)->toLatinDigits($sheba);
        $sheba = strtoupper(preg_replace('/[\s\-]/', '', $sheba));
        $sheba = preg_replace('/^IR/', '', $sheba);
        return preg_match('/^\d{24}$/', $sheba) ? 'IR' . $sheba : null;
    }
}

if (! function_exists('is_valid_bank_card')) {
    function is_valid_bank_card(string $card): bool
    {
        $card = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)
            ->toLatinDigits($card);
        $card = preg_replace('/[\s\-]/', '', $card);

        if (! preg_match('/^\d{16}$/', $card)) return false;
        if (preg_match('/^(\d)\1{15}$/', $card)) return false;

        return \Sghazanfari\FilamentPersian\Rules\BankCardNumber::detectBank($card) !== null;
    }
}

if (! function_exists('detect_bank_from_card')) {
    function detect_bank_from_card(string $card): ?string
    {
        return \Sghazanfari\FilamentPersian\Rules\BankCardNumber::detectBank($card);
    }
}

if (! function_exists('format_bank_card')) {
    function format_bank_card(string $card): string
    {
        $card = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)
            ->toLatinDigits($card);
        $card = preg_replace('/[\s\-]/', '', $card);

        if (strlen($card) !== 16) return $card;

        return implode('-', str_split($card, 4));
    }
}

if (! function_exists('is_valid_legal_entity_national_id')) {
    function is_valid_legal_entity_national_id(string $id): bool
    {
        $id = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)
            ->toLatinDigits($id);
        $id = preg_replace('/[\s\-]/', '', $id);

        if (! preg_match('/^\d{11}$/', $id)) return false;
        if (preg_match('/^(\d)\1{10}$/', $id)) return false;
        if ($id === '00000000000') return false;

        $weights = [2, 4, 6, 8, 10, 12, 14, 16, 18, 20];
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += ((int) $id[$i]) * $weights[$i];
        }

        $remainder = $sum % 11;
        $check = (int) $id[10];

        return $remainder === $check || ($remainder === 10 && $check === 0);
    }
}

if (! function_exists('is_valid_economic_code')) {
    function is_valid_economic_code(string $code): bool
    {
        $code = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)
            ->toLatinDigits($code);
        $code = preg_replace('/[\s\-]/', '', $code);

        if (! preg_match('/^\d{14}$/', $code)) return false;

        $blocked = [
            '00000000000000', '11111111111111', '22222222222222',
            '33333333333333', '44444444444444', '55555555555555',
            '66666666666666', '77777777777777', '88888888888888',
            '99999999999999',
        ];

        return ! in_array($code, $blocked, true);
    }
}

if (! function_exists('is_valid_vehicle_plate')) {
    function is_valid_vehicle_plate(string $plate): bool
    {
        $plate = \Sghazanfari\FilamentPersian\Rules\VehiclePlate::normalize($plate);

        if ($plate === '') return false;

        $letters = \Sghazanfari\FilamentPersian\Rules\VehiclePlate::ALLOWED_LETTERS;

        // الگوی ۱: 2 رقم + 1 حرف + 3 رقم
        if (preg_match('/^(\d{2})([\p{L}]+)(\d{3})$/u', $plate, $m)) {
            return in_array($m[2], $letters, true);
        }

        // الگوی ۲: با کد ایران
        if (preg_match('/^(\d{2})([\p{L}]+)(\d{3})(?:ایران)?(\d{2})$/u', $plate, $m)) {
            if (! in_array($m[2], $letters, true)) return false;
            $code = (int) $m[4];
            return $code >= 1 && $code <= 99;
        }

        return false;
    }
}

if (! function_exists('normalize_vehicle_plate')) {
    function normalize_vehicle_plate(string $plate): string
    {
        return \Sghazanfari\FilamentPersian\Rules\VehiclePlate::normalize($plate);
    }
}

if (! function_exists('format_vehicle_plate')) {
    function format_vehicle_plate(string $plate): string
    {
        return \Sghazanfari\FilamentPersian\Rules\VehiclePlate::format($plate);
    }
}

if (! function_exists('is_valid_social_security_number')) {
    function is_valid_social_security_number(string $number): bool
    {
        $number = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)
            ->toLatinDigits($number);
        $number = preg_replace('/[\s\-]/', '', $number);

        if (! preg_match('/^\d{10}$/', $number)) return false;

        $blocked = [
            '0000000000', '1111111111', '2222222222', '3333333333',
            '4444444444', '5555555555', '6666666666', '7777777777',
            '8888888888', '9999999999',
        ];

        return ! in_array($number, $blocked, true);
    }
}

if (! function_exists('format_social_security_number')) {
    function format_social_security_number(string $number): string
    {
        $number = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)
            ->toLatinDigits($number);
        $number = preg_replace('/[\s\-]/', '', $number);

        if (strlen($number) !== 10) return $number;

        // فرمت: 123-456789-0
        return substr($number, 0, 3) . '-' . substr($number, 3, 6) . '-' . substr($number, 9, 1);
    }
}

if (! function_exists('is_valid_stock_exchange_code')) {
    function is_valid_stock_exchange_code(string $code): bool
    {
        $code = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)
            ->toLatinDigits($code);
        $code = preg_replace('/[\s\-]/', '', $code);

        if (! preg_match('/^\d{11}$/', $code)) return false;

        $blocked = [
            '00000000000', '11111111111', '22222222222', '33333333333',
            '44444444444', '55555555555', '66666666666', '77777777777',
            '88888888888', '99999999999',
        ];

        return ! in_array($code, $blocked, true);
    }
}

if (! function_exists('is_valid_passport_number')) {
    function is_valid_passport_number(string $passport): bool
    {
        $passport = \Sghazanfari\FilamentPersian\Rules\PassportNumber::normalize($passport);

        if ($passport === '') return false;

        // حرف + ۸ رقم
        if (preg_match('/^[A-Z]\d{8}$/', $passport)) return true;

        // ۹ رقم خالص (غیر تکراری)
        if (preg_match('/^\d{9}$/', $passport)) {
            return ! preg_match('/^(\d)\1{8}$/', $passport);
        }

        // ۲ حرف + ۷ رقم
        if (preg_match('/^[A-Z]{2}\d{7}$/', $passport)) return true;

        return false;
    }
}

if (! function_exists('normalize_passport_number')) {
    function normalize_passport_number(string $passport): string
    {
        return \Sghazanfari\FilamentPersian\Rules\PassportNumber::normalize($passport);
    }
}

if (! function_exists('format_passport_number')) {
    function format_passport_number(string $passport): string
    {
        return \Sghazanfari\FilamentPersian\Rules\PassportNumber::format($passport);
    }
}

if (! function_exists('iran_provinces')) {
    function iran_provinces(): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::provinces();
    }
}

if (! function_exists('iran_province')) {
    function iran_province(int $id): ?array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::province($id);
    }
}

if (! function_exists('iran_provinces_for_select')) {
    function iran_provinces_for_select(): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::provincesForSelect();
    }
}

if (! function_exists('iran_banks')) {
    function iran_banks(): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::banks();
    }
}

if (! function_exists('iran_bank')) {
    function iran_bank(string $code): ?array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::bank($code);
    }
}

if (! function_exists('detect_bank_from_card_full')) {
    function detect_bank_from_card_full(string $card): ?array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::bankByCardPrefix($card);
    }
}

if (! function_exists('iran_banks_for_select')) {
    function iran_banks_for_select(): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::banksForSelect();
    }
}

if (! function_exists('tehran_regions')) {
    function tehran_regions(): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::tehranRegions();
    }
}

if (! function_exists('tehran_region')) {
    function tehran_region(int $id): ?array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::tehranRegion($id);
    }
}

if (! function_exists('tehran_regions_for_select')) {
    function tehran_regions_for_select(): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::tehranRegionsForSelect();
    }
}

if (! function_exists('iran_cities')) {
    function iran_cities(int $provinceId)
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::cities($provinceId);
    }
}

if (! function_exists('iran_cities_for_select')) {
    function iran_cities_for_select(?int $provinceId = null): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::citiesForSelect($provinceId);
    }
}

if (! function_exists('iran_city')) {
    function iran_city(int $id): ?array
    {
        return \Sghazanfari\FilamentPersian\Support\IranData::city($id);
    }
}

if (! function_exists('is_iran_holiday')) {
    function is_iran_holiday(\Carbon\Carbon|string $date): bool
    {
        return \Sghazanfari\FilamentPersian\Support\IranHolidays::isHoliday($date);
    }
}

if (! function_exists('iran_holiday_names')) {
    function iran_holiday_names(\Carbon\Carbon|string $date): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranHolidays::holidayNames($date);
    }
}

if (! function_exists('iran_holidays_of_year')) {
    function iran_holidays_of_year(int $jalaliYear): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranHolidays::holidaysOfYear($jalaliYear);
    }
}

if (! function_exists('iran_holidays_between')) {
    function iran_holidays_between(\Carbon\Carbon|string $from, \Carbon\Carbon|string $until): array
    {
        return \Sghazanfari\FilamentPersian\Support\IranHolidays::holidaysBetween($from, $until);
    }
}

if (! function_exists('next_working_day')) {
    function next_working_day(\Carbon\Carbon|string $date, int $skip = 1): \Carbon\Carbon
    {
        return \Sghazanfari\FilamentPersian\Support\IranHolidays::nextWorkingDay($date, $skip);
    }
}

if (! function_exists('previous_working_day')) {
    function previous_working_day(\Carbon\Carbon|string $date, int $skip = 1): \Carbon\Carbon
    {
        return \Sghazanfari\FilamentPersian\Support\IranHolidays::previousWorkingDay($date, $skip);
    }
}

if (! function_exists('working_days_between')) {
    function working_days_between(\Carbon\Carbon|string $from, \Carbon\Carbon|string $until): int
    {
        return \Sghazanfari\FilamentPersian\Support\IranHolidays::workingDaysBetween($from, $until);
    }
}

if (! function_exists('is_working_time')) {
    function is_working_time(\Carbon\Carbon|string|null $moment = null): bool
    {
        return \Sghazanfari\FilamentPersian\Support\WorkingHours::isWorkingTime($moment);
    }
}

if (! function_exists('is_working_day')) {
    function is_working_day(int $dayOfWeek): bool
    {
        return \Sghazanfari\FilamentPersian\Support\WorkingHours::isWorkingDay($dayOfWeek);
    }
}

if (! function_exists('next_working_moment')) {
    function next_working_moment(\Carbon\Carbon|string|null $from = null): \Carbon\Carbon
    {
        return \Sghazanfari\FilamentPersian\Support\WorkingHours::nextWorkingMoment($from);
    }
}

if (! function_exists('next_end_moment')) {
    function next_end_moment(\Carbon\Carbon|string|null $from = null): \Carbon\Carbon
    {
        return \Sghazanfari\FilamentPersian\Support\WorkingHours::nextEndMoment($from);
    }
}

if (! function_exists('weekly_schedule')) {
    function weekly_schedule(): array
    {
        return \Sghazanfari\FilamentPersian\Support\WorkingHours::weeklySchedule();
    }
}

if (! function_exists('working_hours_weekly_total')) {
    function working_hours_weekly_total(): float
    {
        return \Sghazanfari\FilamentPersian\Support\WorkingHours::weeklyHours();
    }
}

if (! function_exists('remaining_working_minutes_today')) {
    function remaining_working_minutes_today(\Carbon\Carbon|string|null $moment = null): int
    {
        return \Sghazanfari\FilamentPersian\Support\WorkingHours::remainingMinutesToday($moment);
    }
}

// =========================================================
// Helper های اعداد (گمشده)
// =========================================================

if (! function_exists('fa_number')) {
    /**
     * فرمت عدد با جداکننده و ممیز فارسی.
     */
    function fa_number(int|float|string $value, int $decimals = 0): string
    {
        $conv = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class);
        $number = (float) $conv->toLatinDigits((string) $value);

        $thousand = \Sghazanfari\FilamentPersian\Settings\FontManager::thousandSeparator();
        $decimal = \Sghazanfari\FilamentPersian\Settings\FontManager::decimalSeparator();

        $formatted = number_format($number, $decimals, $decimal, $thousand);

        if (\Sghazanfari\FilamentPersian\Settings\FontManager::persianNumbersEnabled()) {
            $formatted = $conv->toPersianDigits($formatted);
        }

        return $formatted;
    }
}

if (! function_exists('fa_money')) {
    /**
     * فرمت مبلغ با واحد پول.
     */
    function fa_money(int|float|string $amount, ?string $currency = null, int $decimals = 0): string
    {
        $currency = $currency ?? \Sghazanfari\FilamentPersian\Settings\FontManager::currency();

        if ($currency === 'none') {
            return fa_number($amount, $decimals);
        }

        $formatted = fa_number($amount, $decimals);

        $symbol = config("filament-persian.numbers.currency.symbols.{$currency}", $currency);

        return $symbol ? "{$formatted} {$symbol}" : $formatted;
    }
}

// =========================================================
// Helper های اعتبارسنجی (گمشده)
// =========================================================

if (! function_exists('is_valid_postal_code')) {
    /**
     * بررسی کد پستی ۱۰ رقمی ایرانی.
     */
    function is_valid_postal_code(string $code): bool
    {
        $code = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class)
            ->toLatinDigits($code);
        $code = preg_replace('/[\s\-]/', '', $code);

        if (! preg_match('/^\d{10}$/', $code)) {
            return false;
        }

        // کدهای تکراری
        if (preg_match('/^(\d)\1{9}$/', $code)) {
            return false;
        }

        return true;
    }
}