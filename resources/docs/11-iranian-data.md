# داده‌های آماده ایران

پکیج شامل داده‌های آماده و به‌روز ایران است که به‌صورت خودکار در دسترس شماست:

- **۳۱ استان** با مرکز و اسلاگ
- **۳۱۵ شهر** مهم ایران
- **۳۰ بانک** ایرانی با پیشوندهای کارت و کد شبا
- **۲۲ منطقه** شهرداری تهران

## استان‌ها

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranProvinceSelect;

IranProvinceSelect::make('province_id')
    ->label('استان'),
```

خروجی: یک Select جستجوپذیر با ۳۱ استان.

### Helper Functions

```php
// همه استان‌ها
iran_provinces();
// [
//     1 => ['name' => 'آذربایجان شرقی', 'slug' => 'east-azerbaijan', 'capital' => 'تبریز'],
//     2 => ['name' => 'آذربایجان غربی', 'slug' => 'west-azerbaijan', 'capital' => 'ارومیه'],
//     ...
// ]

// یک استان
iran_province(8);
// ['name' => 'تهران', 'slug' => 'tehran', 'capital' => 'تهران']

// آرایه آماده Select
iran_provinces_for_select();
// [1 => 'آذربایجان شرقی', 2 => 'آذربایجان غربی', ..., 31 => 'یزد']
```

### از کلاس `IranData`

```php
use Sghazanfari\FilamentPersian\Support\IranData;

IranData::provinces();
IranData::province(8);
IranData::provinceBySlug('tehran');
IranData::provincesForSelect();
```

---

## شهرها

> **پیش‌نیاز:** قبل از استفاده، باید شهرها در دیتابیس باشند. یک بار این دستور را بزن:
>
> ```bash
> php artisan filament-persian:seed-cities
> ```

### کامپوننت فرم (وابسته به استان)

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranProvinceSelect;
use Sghazanfari\FilamentPersian\Forms\Components\IranCitySelect;

IranProvinceSelect::make('province_id')
    ->label('استان')
    ->resetCityField('city_id'),   // ← وقتی استان عوض شد، شهر پاک شود

IranCitySelect::make('city_id')
    ->label('شهر')
    ->provinceField('province_id'),  // ← از کدام فیلد استان بخواند
```

**رفتار:**
- تا وقتی استان انتخاب نشده، شهر **disabled** است
- با تغییر استان، لیست شهرها به‌روز می‌شود
- با تغییر استان، شهر قبلی **خودکار پاک می‌شود**

### Helper Functions

```php
// شهرهای یک استان
iran_cities(8);   // کلکسیون ۱۵ شهر تهران

// آرایه آماده Select (همه شهرها)
iran_cities_for_select();      // [id => 'نام شهر', ...]

// آرایه آماده Select برای یک استان
iran_cities_for_select(8);     // [id => 'تهران', id => 'اسلامشهر', ...]

// یک شهر
iran_city(123);   // ['id' => 123, 'province_id' => 8, 'name' => 'تهران', ...]
```

### مدل `IranCity`

```php
use Sghazanfari\FilamentPersian\Models\IranCity;

// شمارش
IranCity::count();                  // 315

// فیلتر بر اساس استان
IranCity::ofProvince(8)->get();     // شهرهای تهران
IranCity::ofProvince(8)->count();   // 15

// فقط مراکز استان
IranCity::capitals()->get();        // 31 استان
IranCity::capitals()->count();      // 31

// جستجو (با نرمال‌سازی ی/ک)
IranCity::search('تهر')->get();
IranCity::search('مشهد')->first();
IranCity::search('كرمانشاه')->first();  // با ک عربی هم کار می‌کند

// دسترسی به نام استان
$city = IranCity::find(1);
$city->province_name;   // "تهران"
```

### کامندهای مرتبط

```bash
# درج شهرها (اگر قبلاً درج شده، نادیده می‌گیرد)
php artisan filament-persian:seed-cities

# حذف همه و درج مجدد
php artisan filament-persian:seed-cities --fresh
```

---

## بانک‌ها

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranBankSelect;

IranBankSelect::make('bank_code')
    ->label('بانک'),
```

خروجی: یک Select با ۳۰ بانک ایرانی.

### Helper Functions

```php
// همه بانک‌ها
iran_banks();
// [
//     [
//         'code' => 'melli',
//         'name' => 'بانک ملی ایران',
//         'name_en' => 'Bank Melli Iran',
//         'prefixes' => ['603799', '606373', '622106', '628157'],
//         'sheba_code' => '017',
//     ],
//     ...
// ]

// یک بانک
iran_bank('melli');
// ['code' => 'melli', 'name' => 'بانک ملی ایران', ...]

// آرایه آماده Select
iran_banks_for_select();
// ['melli' => 'بانک ملی ایران', 'saderat' => 'بانک صادرات ایران', ...]
```

### تشخیص بانک از روی شماره کارت

```php
detect_bank_from_card_full('6037997512345678');
// ['code' => 'melli', 'name' => 'بانک ملی ایران', ...]

// اگر ناشناخته باشد
detect_bank_from_card_full('1111111111111111');
// null
```

### استفاده در فرم با تشخیص خودکار

```php
use Filament\Forms\Get;
use Sghazanfari\FilamentPersian\Forms\Components\IranianBankCardInput;
use Sghazanfari\FilamentPersian\Support\IranData;

IranianBankCardInput::make('bank_card')
    ->label('شماره کارت')
    ->live(onBlur: true)
    ->afterStateUpdated(function (Get $get, callable $set, $state) {
        $bank = IranData::bankByCardPrefix($state ?? '');
        if ($bank) {
            $set('bank_code', $bank['code']);
        }
    }),

IranBankSelect::make('bank_code')
    ->label('بانک'),
```

با این تنظیم، وقتی کاربر شماره کارت را وارد می‌کند، بانک خودکار انتخاب می‌شود.

---

## مناطق تهران

### Helper Functions

```php
// همه مناطق
tehran_regions();
// [
//     1 => ['name' => 'منطقه ۱', 'neighborhoods' => ['تجریش', 'نیاوران', 'الهیه', ...]],
//     2 => ['name' => 'منطقه ۲', 'neighborhoods' => ['صادقیه', 'شهرآرا', ...]],
//     ...
//     22 => ['name' => 'منطقه ۲۲', ...],
// ]

// یک منطقه
tehran_region(1);
// ['name' => 'منطقه ۱', 'neighborhoods' => [...]]

// آرایه آماده Select
tehran_regions_for_select();
// [1 => 'منطقه ۱', 2 => 'منطقه ۲', ..., 22 => 'منطقه ۲۲']
```

### کامپوننت Select (ساده)

```php
use Filament\Forms\Components\Select;

Select::make('tehran_region')
    ->label('منطقه تهران')
    ->options(tehran_regions_for_select())
    ->searchable()
    ->native(false),
```

---

## مثال کامل: فرم آدرس ایران

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranProvinceSelect;
use Sghazanfari\FilamentPersian\Forms\Components\IranCitySelect;
use Filament\Forms\Components\TextInput;

class AddressForm
{
    public static function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                IranProvinceSelect::make('province_id')
                    ->label('استان')
                    ->resetCityField('city_id')
                    ->required(),

                IranCitySelect::make('city_id')
                    ->label('شهر')
                    ->provinceField('province_id')
                    ->required(),

                TextInput::make('street')
                    ->label('خیابان')
                    ->required(),

                TextInput::make('postal_code')
                    ->label('کد پستی')
                    ->rule(new \Sghazanfari\FilamentPersian\Rules\PostalCode())
                    ->maxLength(10),
            ]);
    }
}
```

---

## مثال کامل: فرم پرداخت با کارت

```php
use Filament\Forms\Get;
use Sghazanfari\FilamentPersian\Forms\Components\IranianBankCardInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranBankSelect;
use Sghazanfari\FilamentPersian\Support\IranData;

class PaymentForm
{
    public static function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                IranianBankCardInput::make('bank_card')
                    ->label('شماره کارت')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (callable $set, $state) {
                        $bank = IranData::bankByCardPrefix($state ?? '');
                        if ($bank) {
                            $set('bank_code', $bank['code']);
                        }
                    }),

                IranBankSelect::make('bank_code')
                    ->label('بانک'),

                \Sghazanfari\FilamentPersian\Forms\Components\IranianShebaInput::make('sheba')
                    ->label('شماره شبا'),
            ]);
    }
}
```

---

## ساختار داده در دیتابیس

### جدول `iran_cities`

| ستون | نوع | توضیح |
|---|---|---|
| `id` | bigint | شناسه |
| `province_id` | tinyint | شناسه استان (1-31) |
| `name` | varchar(100) | نام شهر |
| `name_en` | varchar(100) | نام انگلیسی (nullable) |
| `slug` | varchar(100) | اسلاگ (nullable) |
| `population` | int | جمعیت (nullable) |
| `is_capital` | boolean | مرکز استان |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**نکته:** جدول استان‌ها در دیتابیس نیست — به‌صورت فایل PHP در `resources/data/provinces.php` نگهداری می‌شود. چون استان‌ها تعدادشان ثابت و کم است.

---

## به‌روزرسانی داده‌ها

اگر شهرها یا بانک‌های جدید اضافه شدند:

```bash
# دریافت آخرین نسخه پکیج
composer update sghazanfari/filament-persian

# به‌روزرسانی شهرها (فقط موارد جدید درج می‌شوند)
php artisan filament-persian:seed-cities

# یا درج مجدد از صفر
php artisan filament-persian:seed-cities --fresh
```

---

## محدودیت‌ها

### تعداد شهرها

پکیج شامل **۳۱۵ شهر** است (از میان ۱۲۰۰+ شهر ایران). اگر شهر خاصی لازم داری که نیست:

1. Fork کن
2. فایل `resources/data/cities.php` را ویرایش کن
3. Pull Request بزن

یا از کد، شهر جدید اضافه کن:

```php
use Sghazanfari\FilamentPersian\Models\IranCity;

IranCity::create([
    'province_id' => 8,
    'name' => 'نام شهر',
    'is_capital' => false,
]);
```

### اعتبارسنجی واقعی

داده‌های این پکیج **ساختاری** هستند. یعنی `تهران` یک شهر معتبر در استان تهران است، ولی این که آیا کد پستی خاص وجود دارد یا نه، اینجا چک نمی‌شود.

برای اعتبارسنجی واقعی (وجود فیزیکی)، نیاز به API شرکت پست داری.

---

## نکته عملکرد

اگر جدول `iran_cities` بزرگ است (بیش از ۱۰ هزار رکورد)، از کش استفاده کن:

```php
use Sghazanfari\FilamentPersian\Support\IranData;

// متدهای `cities` و `citiesForSelect` هر بار به دیتابیس می‌زنند.
// برای بهینه‌سازی، نتیجه را کش کن:

$cities = Cache::remember('iran_cities_province_8', 3600, function () {
    return iran_cities_for_select(8);
});
```

---

## در ادامه

- [اعتبارسنجی‌های ایرانی](validations) — کد ملی، موبایل، شبا، ...
- [کامپوننت‌ها](components) — فهرست کامل کامپوننت‌ها
- [تنظیمات](settings) — شخصی‌سازی پکیج