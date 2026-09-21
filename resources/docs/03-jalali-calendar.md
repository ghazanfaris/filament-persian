# تقویم جلالی

ستون فقرات این پکیج. سه کامپوننت اصلی:

- **`JalaliDatePicker`** — برای فرم‌ها (انتخاب)
- **`JalaliDateColumn`** — برای جداول (نمایش)
- **`JalaliDateFilter`** — برای فیلترها (بازه)

## کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\JalaliDatePicker;

// فقط تاریخ
JalaliDatePicker::make('birthday')
    ->label('تاریخ تولد'),

// تاریخ + ساعت
JalaliDatePicker::make('event_date')
    ->label('تاریخ رویداد')
    ->withTime(),

// تاریخ + ساعت + ثانیه
JalaliDatePicker::make('created_at')
    ->label('زمان دقیق')
    ->withTime(withSeconds: true),
```

### API

| متد | توضیح |
|---|---|
| `withTime(bool $withSeconds = false)` | نمایش ساعت |
| `withSeconds(bool $c = true)` | نمایش ثانیه |

### رفتار

- کاربر روی فیلد کلیک می‌کند → تقویم شمسی باز می‌شود
- تاریخ انتخاب می‌شود → در input نمایش داده می‌شود (به شمسی)
- ذخیره → به **میلادی** تبدیل و در دیتابیس ذخیره می‌شود
- Edit → مقدار میلادی خوانده و به **شمسی** نمایش داده می‌شود

## ستون جدول

```php
use Sghazanfari\FilamentPersian\Tables\Columns\JalaliDateColumn;

// تاریخ و ساعت
JalaliDateColumn::make('created_at')
    ->label('تاریخ ایجاد')
    ->withTime()
    ->sortable(),

// فقط تاریخ
JalaliDateColumn::make('birthday')
    ->label('تاریخ تولد')
    ->sortable(),

// اختلاف انسانی: «۳ روز پیش»
JalaliDateColumn::make('published_at')
    ->label('انتشار')
    ->human(),

// فرمت دلخواه
JalaliDateColumn::make('event_date')
    ->jalaliFormat('l j F Y'),  // دوشنبه ۱۲ مرداد ۱۴۰۳
```

## فیلتر بازه

```php
use Sghazanfari\FilamentPersian\Tables\Filters\JalaliDateFilter;

JalaliDateFilter::make('created_range')
    ->column('created_at')
    ->label('بازه ایجاد')
    ->withTime(),
```

خروجی: دو فیلد «از تاریخ» و «تا تاریخ» با تقویم شمسی.

## تبدیل خودکار همه ستون‌ها

اگر می‌خواهی همه ستون‌های تاریخ مدل خودکار جلالی شوند:

```php
use Sghazanfari\FilamentPersian\Concerns\AutoJalaliDates;

class EventResource extends Resource
{
    use AutoJalaliDates;

    public static function table(Table $table): Table
    {
        return static::autoJalaliDates(EventsTable::configure($table));
    }
}
```

پکیج از `casts` مدل، ستون‌های `date` و `datetime` را پیدا می‌کند و خودکار تبدیل می‌کند.

## Trait برای مدل‌ها

```php
use Sghazanfari\FilamentPersian\Concerns\HasJalaliDates;

class User extends Authenticatable
{
    use HasJalaliDates;
}

// استفاده:
$user->jalali('created_at');       // ۱۴۰۳/۰۵/۱۲
$user->jalaliHuman('created_at');  // ۲ ساعت پیش

// Scope:
User::whereJalaliBetween('created_at', 1403, 1, 1, 1403, 12, 29)->get();
```

## Helper Functions

```php
// تبدیل میلادی → جلالی
jalali(now());                          // [1403, 5, 12]
jalali('2024-08-02');                   // [1403, 5, 12]

// فرمت
jalali_format(now());                   // ۱۴۰۳/۰۵/۱۲
jalali_format(now(), 'Y/m/d H:i');      // ۱۴۰۳/۰۵/۱۲ ۱۴:۳۰
jalali_format(now(), 'j F Y');          // ۱۲ مرداد ۱۴۰۳
jalali_format(now(), 'l j F Y');        // دوشنبه ۱۲ مرداد ۱۴۰۳

// الان
jalali_now();                           // ۱۴۰۳/۰۵/۱۲ ۱۴:۳۰

// تبدیل جلالی → Carbon
to_jalali_carbon(1403, 5, 12);          // Carbon میلادی
```

## الگوهای فرمت

| حرف | معنی | مثال |
|---|---|---|
| `Y` | سال کامل | ۱۴۰۳ |
| `y` | دو رقم آخر سال | ۰۳ |
| `m` | ماه دو رقمی | ۰۵ |
| `n` | ماه بدون صفر | ۵ |
| `d` | روز دو رقمی | ۱۲ |
| `j` | روز بدون صفر | ۱۲ |
| `F` | نام ماه | مرداد |
| `M` | نام ماه (کوتاه) | مرداد |
| `l` | نام روز هفته | دوشنبه |
| `D` | حرف روز هفته | د |
| `H` | ساعت 24 | ۱۴ |
| `i` | دقیقه | ۳۰ |
| `s` | ثانیه | ۴۵ |

## الگوریتم

پکیج از الگوریتم **Borkowski** (jdf) برای تبدیل استفاده می‌کند:

- دقیق برای همه سال‌ها
- بدون وابستگی خارجی
- سریع
- تست‌شده در مرزها (سال کبیسه، ۲۹/۳۰ اسفند)

## نکته مهم: ذخیره در دیتابیس

**همیشه میلادی ذخیره می‌شود.** این برای:

- سازگاری با `Carbon` و `DateTime`
- کار با Query Builder
- Sorting و Filtering درست
- Migration به سیستم‌های دیگر

تنها در **نمایش** و **انتخاب** کاربر، شمسی می‌شود.

## شخصی‌سازی

از **تنظیمات ظاهری** → تب **تاریخ**:

- تقویم پیش‌فرض: شمسی یا میلادی
- فرمت پیش‌فرض تاریخ
- اولین روز هفته: شنبه یا...