# کامپوننت‌های فرم و جدول

فهرست کامل کامپوننت‌های این پکیج — دسته‌بندی‌شده برای مرجع سریع.

## فهرست کلی

| دسته | تعداد | توضیح |
|---|---|---|
| 📅 تاریخ و تقویم | ۳ | DatePicker, Column, Filter |
| ✅ ورودی‌های ایرانی | ۸ | موبایل، کد ملی، شبا، کارت، ... |
| 🔢 اعداد و مبالغ | ۴ | NumberInput, MoneyColumn, ... |
| 🗺️ داده‌های آماده ایران | ۳ | Province, City, Bank Select |
| 📊 ویجت و نمودار | ۲ | StatsWidget, ChartWidget |
| 🧩 Traits و Macros | ۴ | AutoJalali, GlobalSearch, ... |

---

## 📅 تاریخ و تقویم

### `JalaliDatePicker`

انتخاب تاریخ شمسی.

```php
use Sghazanfari\FilamentPersian\Forms\Components\JalaliDatePicker;

JalaliDatePicker::make('birthday')
    ->label('تاریخ تولد'),

JalaliDatePicker::make('event_date')
    ->label('تاریخ رویداد')
    ->withTime(),

JalaliDatePicker::make('created_at')
    ->label('زمان دقیق')
    ->withTime(withSeconds: true),
```

| متد | توضیح |
|---|---|
| `withTime(bool $withSeconds = false)` | نمایش ساعت |
| `withSeconds(bool $c = true)` | نمایش ثانیه |

### `JalaliDateColumn`

نمایش تاریخ شمسی در جدول.

```php
use Sghazanfari\FilamentPersian\Tables\Columns\JalaliDateColumn;

JalaliDateColumn::make('created_at')
    ->label('تاریخ ایجاد')
    ->withTime()
    ->sortable(),

JalaliDateColumn::make('published_at')
    ->label('انتشار')
    ->human(),   // «۳ روز پیش»

JalaliDateColumn::make('event_date')
    ->jalaliFormat('l j F Y'),  // دوشنبه ۱۲ مرداد ۱۴۰۳
```

### `JalaliDateFilter`

فیلتر بازه تاریخ.

```php
use Sghazanfari\FilamentPersian\Tables\Filters\JalaliDateFilter;

JalaliDateFilter::make('created_range')
    ->column('created_at')
    ->label('بازه ایجاد')
    ->withTime(),
```

---

## ✅ ورودی‌های ایرانی (۸ کامپوننت)

### `IranianMobileInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianMobileInput;

IranianMobileInput::make('mobile')
    ->label('موبایل'),
```

- پشتیبانی از `+98`, `0098`, `98`
- حذف خودکار فاصله و خط تیره
- تبدیل ارقام فارسی
- ذخیره به‌صورت `09xxxxxxxxx`

### `IranianNationalCodeInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianNationalCodeInput;

IranianNationalCodeInput::make('national_code')
    ->label('کد ملی'),
```

- ۱۰ رقم با checksum
- رد کدهای تکراری

### `IranianShebaInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianShebaInput;

IranianShebaInput::make('sheba')
    ->label('شماره شبا'),
```

- `IR` خودکار اضافه می‌شود
- الگوریتم IBAN

### `IranianBankCardInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianBankCardInput;

IranianBankCardInput::make('bank_card')
    ->label('شماره کارت'),
```

- ماسک خودکار `6037-9975-1234-5678`
- تشخیص بانک از روی پیشوند

### `IranianLegalEntityIdInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianLegalEntityIdInput;

IranianLegalEntityIdInput::make('legal_id')
    ->label('شناسه ملی حقوقی'),
```

- ۱۱ رقمی با checksum

### `IranianEconomicCodeInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianEconomicCodeInput;

IranianEconomicCodeInput::make('economic_code')
    ->label('شماره اقتصادی'),
```

- ۱۴ رقمی

### `IranianVehiclePlateInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianVehiclePlateInput;

IranianVehiclePlateInput::make('vehicle_plate')
    ->label('شماره پلاک'),
```

- پشتیبانی از ۳ فرمت پلاک ایران

### `IranianSocialSecurityInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianSocialSecurityInput;

IranianSocialSecurityInput::make('ssn')
    ->label('شماره بیمه'),
```

- ماسک خودکار `123-456789-0`

### `IranianStockExchangeCodeInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianStockExchangeCodeInput;

IranianStockExchangeCodeInput::make('stock_code')
    ->label('کد بورسی'),
```

- ۱۱ رقمی

### `IranianPassportInput`

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianPassportInput;

IranianPassportInput::make('passport')
    ->label('شماره گذرنامه'),
```

- پشتیبانی از `A12345678`, `123456789`, `AB1234567`

---

## 🔢 اعداد و مبالغ

### `PersianNumberInput`

ورودی عدد با فرمت خودکار.

```php
use Sghazanfari\FilamentPersian\Forms\Components\PersianNumberInput;

PersianNumberInput::make('quantity')
    ->label('تعداد'),

PersianNumberInput::make('amount')
    ->label('مبلغ')
    ->money('IRT')
    ->decimals(0),
```

| متد | توضیح |
|---|---|
| `money(?string $currency)` | با واحد پول |
| `decimals(int $n)` | تعداد رقم اعشار |

### `PersianMoneyColumn`

ستون مبلغ.

```php
use Sghazanfari\FilamentPersian\Tables\Columns\PersianMoneyColumn;

PersianMoneyColumn::make('amount')
    ->label('مبلغ')
    ->currency('IRT')
    ->decimals(0)
    ->sortable(),
```

### `PersianTextColumn`

ستون متن با اعداد فارسی.

```php
use Sghazanfari\FilamentPersian\Tables\Columns\PersianTextColumn;

PersianTextColumn::make('id')
    ->label('شناسه')
    ->thousands(),

PersianTextColumn::make('mobile')
    ->label('موبایل'),

PersianTextColumn::make('national_code')
    ->label('کد ملی')
    ->withoutPersianDigits(),
```

| متد | توضیح |
|---|---|
| `thousands(bool $c = true)` | جداکننده هزارگان |
| `withoutPersianDigits()` | نگه‌داشتن لاتین |

---

## 🗺️ داده‌های آماده ایران

> **پیش‌نیاز:** قبل از استفاده از شهرها، باید seed کنی:
>
> ```bash
> php artisan filament-persian:seed-cities
> ```

### `IranProvinceSelect`

انتخاب استان.

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranProvinceSelect;

IranProvinceSelect::make('province_id')
    ->label('استان')
    ->resetCityField('city_id'),   // وقتی استان عوض شد، شهر پاک شود
```

- ۳۱ استان
- جستجوپذیر
- Auto-reset شهر وابسته

### `IranCitySelect`

انتخاب شهر وابسته به استان.

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranCitySelect;

IranCitySelect::make('city_id')
    ->label('شهر')
    ->provinceField('province_id'),
```

- تا استان انتخاب نشود، **disabled** است
- ۳۱۵ شهر ایران
- فیلتر خودکار بر اساس استان

### `IranBankSelect`

انتخاب بانک.

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranBankSelect;

IranBankSelect::make('bank_code')
    ->label('بانک'),
```

- ۳۰ بانک ایرانی
- با کد و نام کامل

### مثال: فرم آدرس کامل

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranProvinceSelect;
use Sghazanfari\FilamentPersian\Forms\Components\IranCitySelect;

IranProvinceSelect::make('province_id')
    ->label('استان')
    ->resetCityField('city_id')
    ->required(),

IranCitySelect::make('city_id')
    ->label('شهر')
    ->provinceField('province_id')
    ->required(),
```

### مثال: تشخیص خودکار بانک از شماره کارت

```php
use Filament\Forms\Get;
use Sghazanfari\FilamentPersian\Forms\Components\IranianBankCardInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranBankSelect;
use Sghazanfari\FilamentPersian\Support\IranData;

IranianBankCardInput::make('bank_card')
    ->label('شماره کارت')
    ->live(onBlur: true)
    ->afterStateUpdated(function (callable $set, $state) {
        $bank = IranData::bankByCardPrefix($state ?? '');
        if ($bank) {
            $set('bank_code', $bank['code']);
        }
    }),

IranBankSelect::make('bank_code')
    ->label('بانک'),
```

---

## 📊 ویجت و نمودار

### `PersianStatsWidget` (انتزاعی)

```php
use Sghazanfari\FilamentPersian\Widgets\PersianStatsWidget;

class DashboardStats extends PersianStatsWidget
{
    protected function getStats(): array
    {
        return [
            $this->makeNumberStat(
                label: 'کاربران',
                value: User::count(),
                description: '۱۲٪ رشد',
                icon: 'heroicon-m-arrow-trending-up',
                color: 'success',
            ),

            $this->makeMoneyStat(
                label: 'درآمد',
                amount: 48_500_000,
                currency: 'IRT',
            ),

            $this->makeDateStat(
                label: 'امروز',
                date: now(),
                pattern: 'l j F Y',
            ),

            $this->makeHumanDateStat(
                label: 'آخرین ورود',
                date: now()->subHours(2),
            ),
        ];
    }
}
```

**متدهای آماده:**

| متد | خروجی |
|---|---|
| `makeNumberStat(...)` | Stat عددی |
| `makeMoneyStat(...)` | Stat مبلغ |
| `makePercentStat(...)` | Stat درصد |
| `makeDateStat(...)` | Stat تاریخ جلالی |
| `makeHumanDateStat(...)` | «۳ روز پیش» |
| `makeTextStat(...)` | متن با اعداد فارسی |
| `growthDescription($curr, $prev)` | توضیح رشد |
| `growthColor($curr, $prev)` | رنگ رشد |
| `growthIcon($curr, $prev)` | آیکون رشد |

### `PersianChartWidget` (انتزاعی)

```php
use Sghazanfari\FilamentPersian\Widgets\PersianChartWidget;

class OrdersChart extends PersianChartWidget
{
    protected ?string $heading = 'سفارش‌ها';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        [$labels, $dates] = $this->lastNDays(30);

        $counts = [];
        foreach ($dates as $date) {
            $counts[] = Order::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                $this->makeDataset('سفارش‌ها', $dates, $counts, 0),
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
```

**متدهای کمکی:**

| متد | خروجی |
|---|---|
| `lastNDays(30)` | ۳۰ روز اخیر + برچسب جلالی |
| `lastNMonths(12)` | ۱۲ ماه اخیر |
| `lastWeek()` | ۷ روز با نام روز |
| `makeDataset(...)` | dataset خطی |
| `makeBarDataset(...)` | dataset ستونی |

---

## 🧩 Traits و Macros

### `HasJalaliDates` (برای مدل‌ها)

```php
use Sghazanfari\FilamentPersian\Concerns\HasJalaliDates;

class User extends Authenticatable
{
    use HasJalaliDates;
}

$user->jalali('created_at');       // ۱۴۰۳/۰۵/۱۲
$user->jalaliHuman('created_at');  // ۲ ساعت پیش

User::whereJalaliBetween('created_at', 1403, 1, 1, 1403, 12, 29)->get();
```

### `AutoJalaliDates` (برای Resource ها)

```php
use Sghazanfari\FilamentPersian\Concerns\AutoJalaliDates;

class EventResource extends Resource
{
    use AutoJalaliDates;

    public static function table(Table $table): Table
    {
        return static::autoJalaliDates(
            EventsTable::configure($table)
        );
    }
}
```

### `HasPersianGlobalSearch`

جستجوی فارسی (ی/ک عربی).

```php
use Sghazanfari\FilamentPersian\Concerns\HasPersianGlobalSearch;

class EventResource extends Resource
{
    use HasPersianGlobalSearch;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'body'];
    }
}
```

### Macro `withJalaliTimestamps()`

اضافه کردن `created_at` و `updated_at` خودکار.

```php
Table::make()
    ->columns([...])
    ->withJalaliTimestamps();

// فقط created_at
->withJalaliTimestamps(withUpdatedAt: false)

// فقط تاریخ بدون ساعت
->withJalaliTimestamps(withTime: false)
```

---

## Helper Functions

### تاریخ

| تابع | خروجی |
|---|---|
| `jalali($date)` | آرایه `[y, m, d]` |
| `jalali_format($date, $pattern)` | رشته فرمت‌شده |
| `jalali_now()` | الان |
| `to_jalali_carbon($y, $m, $d)` | Carbon میلادی |

### اعداد

| تابع | خروجی |
|---|---|
| `fa_digits($value)` | ارقام فارسی |
| `en_digits($value)` | ارقام لاتین |
| `fa_number($value)` | عدد با جداکننده |
| `fa_money($value, $currency)` | مبلغ |

### اعتبارسنجی

| تابع | بررسی |
|---|---|
| `is_valid_national_code($code)` | کد ملی |
| `is_valid_mobile($mobile)` | موبایل |
| `normalize_mobile($mobile)` | نرمال‌سازی موبایل |
| `is_valid_sheba($sheba)` | شبا |
| `normalize_sheba($sheba)` | نرمال‌سازی شبا |
| `is_valid_postal_code($code)` | کد پستی |
| `is_valid_bank_card($card)` | شماره کارت |
| `detect_bank_from_card($card)` | تشخیص بانک (رشته) |
| `detect_bank_from_card_full($card)` | تشخیص بانک (آرایه) |
| `format_bank_card($card)` | فرمت ۴-۴-۴-۴ |
| `is_valid_legal_entity_national_id($id)` | شناسه ملی حقوقی |
| `is_valid_economic_code($code)` | شماره اقتصادی |
| `is_valid_vehicle_plate($plate)` | پلاک خودرو |
| `format_vehicle_plate($plate)` | فرمت پلاک |
| `is_valid_social_security_number($ssn)` | شماره بیمه |
| `format_social_security_number($ssn)` | فرمت 3-6-1 |
| `is_valid_stock_exchange_code($code)` | کد بورسی |
| `is_valid_passport_number($passport)` | شماره گذرنامه |
| `format_passport_number($passport)` | فرمت A - 12345678 |

### داده‌های آماده ایران

| تابع | خروجی |
|---|---|
| `iran_provinces()` | آرایه ۳۱ استان |
| `iran_province($id)` | اطلاعات یک استان |
| `iran_provinces_for_select()` | `[id => name]` |
| `iran_cities($provinceId)` | کلکسیون شهرها |
| `iran_cities_for_select($provinceId)` | `[id => name]` |
| `iran_city($id)` | اطلاعات یک شهر |
| `iran_banks()` | آرایه ۳۰ بانک |
| `iran_bank($code)` | اطلاعات یک بانک |
| `iran_banks_for_select()` | `[code => name]` |
| `tehran_regions()` | آرایه ۲۲ منطقه |
| `tehran_region($id)` | اطلاعات یک منطقه |
| `tehran_regions_for_select()` | `[id => name]` |

---

## مثال کامل: فرم سفارش

```php
<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Sghazanfari\FilamentPersian\Forms\Components\AutoJalaliDates;
use Sghazanfari\FilamentPersian\Forms\Components\IranBankSelect;
use Sghazanfari\FilamentPersian\Forms\Components\IranCitySelect;
use Sghazanfari\FilamentPersian\Forms\Components\IranProvinceSelect;
use Sghazanfari\FilamentPersian\Forms\Components\IranianBankCardInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranianMobileInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranianNationalCodeInput;
use Sghazanfari\FilamentPersian\Forms\Components\JalaliDatePicker;
use Sghazanfari\FilamentPersian\Forms\Components\PersianNumberInput;
use Sghazanfari\FilamentPersian\Support\IranData;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('order_number')
                ->label('شماره سفارش')
                ->required(),

            IranianMobileInput::make('customer_mobile')
                ->label('موبایل مشتری')
                ->required(),

            IranianNationalCodeInput::make('customer_national_code')
                ->label('کد ملی مشتری'),

            IranProvinceSelect::make('province_id')
                ->label('استان')
                ->resetCityField('city_id'),

            IranCitySelect::make('city_id')
                ->label('شهر')
                ->provinceField('province_id'),

            Textarea::make('address')
                ->label('آدرس کامل')
                ->rows(3),

            JalaliDatePicker::make('delivery_date')
                ->label('تاریخ تحویل')
                ->withTime(),

            IranianBankCardInput::make('bank_card')
                ->label('شماره کارت پرداخت')
                ->live(onBlur: true)
                ->afterStateUpdated(function (callable $set, $state) {
                    $bank = IranData::bankByCardPrefix($state ?? '');
                    if ($bank) {
                        $set('bank_code', $bank['code']);
                    }
                }),

            IranBankSelect::make('bank_code')
                ->label('بانک'),

            PersianNumberInput::make('total_amount')
                ->label('مبلغ کل')
                ->money('IRT'),
        ]);
    }
}
```

---

## مثال کامل: جدول سفارش‌ها

```php
<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Sghazanfari\FilamentPersian\Tables\Columns\JalaliDateColumn;
use Sghazanfari\FilamentPersian\Tables\Columns\PersianMoneyColumn;
use Sghazanfari\FilamentPersian\Tables\Columns\PersianTextColumn;
use Sghazanfari\FilamentPersian\Tables\Filters\JalaliDateFilter;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                PersianTextColumn::make('order_number')
                    ->label('شماره'),

                PersianTextColumn::make('customer_mobile')
                    ->label('موبایل'),

                JalaliDateColumn::make('delivery_date')
                    ->label('تحویل')
                    ->withTime()
                    ->sortable(),

                PersianMoneyColumn::make('total_amount')
                    ->label('مبلغ')
                    ->currency('IRT')
                    ->sortable(),
            ])
            ->filters([
                JalaliDateFilter::make('created_range')
                    ->column('created_at')
                    ->label('بازه ایجاد')
                    ->withTime(),
            ]);
    }
}
```