# تعطیلات و ساعت کاری

پکیج شامل دو قابلیت کلیدی برای کسب‌وکارهای ایرانی است:

- 📅 **تعطیلات رسمی ایران** — شمسی + قمری + جمعه‌ها
- ⏰ **ساعت کاری اداری** — قابل تنظیم از پنل

---

## تعطیلات رسمی

### انواع تعطیلات

پکیج سه نوع تعطیلی را می‌شناسد:

| نوع | توضیح | مثال |
|---|---|---|
| **شمسی** | ثابت هر سال | نوروز، ۲۲ بهمن، ۲۹ اسفند |
| **قمری** | متغیر سالانه | محرم، صفر، رمضان، عید فطر |
| **هفتگی** | هر جمعه | جمعه |

### بررسی تعطیلی

```php
use Sghazanfari\FilamentPersian\Support\IranHolidays;

// آیا این تاریخ تعطیل است؟
IranHolidays::isHoliday('2025-03-21');  // true (نوروز)
IranHolidays::isHoliday(now());          // بستگی به امروز

// نام مناسبت‌ها
IranHolidays::holidayNames('2025-03-21');
// ['جشن نوروز', 'عید نوروز']

// تعطیلات یک سال شمسی
IranHolidays::holidaysOfYear(1404);  // آرایه کامل

// تعطیلات بین دو تاریخ
IranHolidays::holidaysBetween('2025-03-20', '2025-04-05');

// روز کاری بعدی
IranHolidays::nextWorkingDay(now());       // اگر امروز جمعه، شنبه

// روز کاری قبلی
IranHolidays::previousWorkingDay(now());

// تعداد روزهای کاری بین دو تاریخ
IranHolidays::workingDaysBetween('2025-03-01', '2025-03-31');
```

### Helper Functions

```php
is_iran_holiday('2025-03-21');           // true
iran_holiday_names('2025-03-21');        // ['جشن نوروز', 'عید نوروز']
iran_holidays_of_year(1404);              // آرایه
iran_holidays_between($from, $until);     // آرایه
next_working_day(now());                  // Carbon
previous_working_day(now());              // Carbon
working_days_between($from, $until);      // int
```

### پوشش تعطیلات قمری

پکیج تعطیلات قمری را برای **۸ سال شمسی** از قبل محاسبه کرده:

| سال شمسی | سال میلادی |
|---|---|
| ۱۴۰۳ | ۲۰۲۴-۲۰۲۵ |
| ۱۴۰۴ | ۲۰۲۵-۲۰۲۶ |
| ۱۴۰۵ | ۲۰۲۶-۲۰۲۷ |
| ۱۴۰۶ | ۲۰۲۷-۲۰۲۸ |
| ۱۴۰۷ | ۲۰۲۸-۲۰۲۹ |
| ۱۴۰۸ | ۲۰۲۹-۲۰۳۰ |
| ۱۴۰۹ | ۲۰۳۰-۲۰۳۱ |
| ۱۴۱۰ | ۲۰۳۱-۲۰۳۲ |

### تعطیلات شمسی (لیست کامل)

| تاریخ | نام |
|---|---|
| ۱ فروردین | جشن نوروز |
| ۲ فروردین | عید نوروز |
| ۳ فروردین | عید نوروز |
| ۴ فروردین | عید نوروز |
| ۱۲ فروردین | روز جمهوری اسلامی |
| ۱۳ فروردین | روز طبیعت (سیزده‌بدر) |
| ۱۴ خرداد | رحلت حضرت امام خمینی |
| ۱۵ خرداد | قیام ۱۵ خرداد |
| ۲۲ بهمن | پیروزی انقلاب اسلامی |
| ۲۹ اسفند | روز ملی شدن صنعت نفت |

### تعطیلات قمری (نمونه ۱۴۰۴)

| تاریخ میلادی | نام |
|---|---|
| ۲۰۲۵-۰۳-۳۱ | عید سعید فطر |
| ۲۰۲۵-۰۴-۰۱ | تعطیل عید فطر |
| ۲۰۲۵-۰۶-۰۶ | عید سعید قربان |
| ۲۰۲۵-۰۶-۱۴ | عید سعید غدیر خم |
| ۲۰۲۵-۰۷-۰۴ | تاسوعای حسینی |
| ۲۰۲۵-۰۷-۰۵ | عاشورای حسینی |
| ۲۰۲۵-۰۸-۱۴ | اربعین حسینی |
| ۲۰۲۵-۰۸-۲۲ | رحلت رسول اکرم |
| ۲۰۲۵-۰۸-۲۴ | شهادت امام رضا |
| ۲۰۲۵-۰۸-۳۱ | شهادت امام حسن عسکری |
| ۲۰۲۵-۰۹-۰۱ | میلاد رسول اکرم |
| ۲۰۲۵-۰۹-۲۵ | شهادت حضرت فاطمه |

---

## ساعت کاری اداری

### تنظیمات پیش‌فرض

پکیج با تنظیمات استاندارد اداری ایران شروع می‌کند:

| روز | ساعت |
|---|---|
| شنبه | ۰۸:۰۰ تا ۱۶:۰۰ |
| یک‌شنبه | ۰۸:۰۰ تا ۱۶:۰۰ |
| دوشنبه | ۰۸:۰۰ تا ۱۶:۰۰ |
| سه‌شنبه | ۰۸:۰۰ تا ۱۶:۰۰ |
| چهارشنبه | ۰۸:۰۰ تا ۱۶:۰۰ |
| پنج‌شنبه | ۰۸:۰۰ تا ۱۳:۰۰ (نیمه‌وقت) |
| جمعه | تعطیل |

**مجموع هفتگی:** ۴۴ ساعت

### بررسی ساعت کاری

```php
use Sghazanfari\FilamentPersian\Support\WorkingHours;

// آیا الان ساعت کاری است؟
WorkingHours::isWorkingTime();

// یک لحظه خاص
WorkingHours::isWorkingTime('2025-05-17 10:00');  // شنبه ۱۰ صبح

// آیا روز کاری است؟
WorkingHours::isWorkingDay(6);  // true (شنبه)
WorkingHours::isWorkingDay(5);  // false (جمعه)

// لحظه کاری بعدی
WorkingHours::nextWorkingMoment();

// لحظه پایان روز کاری
WorkingHours::nextEndMoment();

// برنامه هفتگی
WorkingHours::weeklySchedule();
// [
//     'شنبه' => '08:00 تا 16:00',
//     ...
// ]

// مجموع ساعت هفتگی
WorkingHours::weeklyHours();  // 44.0

// دقیقه باقی‌مانده امروز
WorkingHours::remainingMinutesToday();
```

### Helper Functions

```php
is_working_time();                       // bool
is_working_day(6);                        // bool
next_working_moment();                    // Carbon
next_end_moment();                        // Carbon
weekly_schedule();                        // آرایه
working_hours_weekly_total();            // float
remaining_working_minutes_today();       // int
```

### سفارشی‌سازی

#### از پنل (توصیه‌شده)

برو به **تنظیمات ظاهری** → تب **⏰ ساعت کاری**:

1. **هر روز هفته** یک سوییچ دارد
2. اگر **روشن** باشد، ساعت شروع و پایان نمایش داده می‌شود
3. اگر **خاموش** باشد، آن روز تعطیل است
4. **مجموع ساعت هفتگی** بالای صفحه به‌روز می‌شود
5. **ذخیره تغییرات**

#### از کد

```php
WorkingHours::setConfig([
    6 => ['start' => '09:00', 'end' => '17:00'], // شنبه
    0 => ['start' => '09:00', 'end' => '17:00'], // یک‌شنبه
    1 => ['start' => '09:00', 'end' => '17:00'], // دوشنبه
    2 => ['start' => '09:00', 'end' => '17:00'], // سه‌شنبه
    3 => ['start' => '09:00', 'end' => '17:00'], // چهارشنبه
    4 => null,                                    // پنج‌شنبه تعطیل
    5 => null,                                    // جمعه تعطیل
]);

// بازگشت به پیش‌فرض
WorkingHours::resetConfig();
```

تنظیمات در `storage/app/filament-persian/settings.json` ذخیره می‌شوند.

---

## کاربردهای عملی

### مثال ۱: نوبت‌دهی کلینیک

```php
use Sghazanfari\FilamentPersian\Support\WorkingHours;
use Sghazanfari\FilamentPersian\Support\IranHolidays;
use Carbon\Carbon;

class AppointmentService
{
    public function findNextSlot(Carbon $after = null): ?Carbon
    {
        $moment = WorkingHours::nextWorkingMoment($after);

        // پیدا کردن اولین نوبت خالی
        while ($this->isSlotTaken($moment)) {
            $moment->addMinutes(30);

            if (! WorkingHours::isWorkingTime($moment)) {
                $moment = WorkingHours::nextWorkingMoment($moment);
            }
        }

        return $moment;
    }

    public function isOpen(Carbon $at): bool
    {
        return WorkingHours::isWorkingTime($at);
    }
}
```

### مثال ۲: محاسبه مهلت پرداخت

```php
// ۳ روز کاری از امروز
$deadline = now();
for ($i = 0; $i < 3; $i++) {
    $deadline = IranHolidays::nextWorkingDay($deadline);
}

echo "مهلت پرداخت: " . jalali_format($deadline, 'l j F Y');
```

### مثال ۳: شمارش روزهای کاری ماه

```php
$from = now()->startOfMonth();
$until = now()->endOfMonth();

$workingDays = IranHolidays::workingDaysBetween($from, $until);

echo "روزهای کاری این ماه: " . fa_digits($workingDays);
```

### مثال ۴: ویجت «وضعیت سیستم»

```php
use Sghazanfari\FilamentPersian\Widgets\PersianStatsWidget;

class SystemStatusWidget extends PersianStatsWidget
{
    protected function getStats(): array
    {
        $isOpen = is_working_time();
        $remaining = remaining_working_minutes_today();

        return [
            $this->makeTextStat(
                label: 'وضعیت',
                value: $isOpen ? 'باز' : 'بسته',
                description: $isOpen
                    ? 'باقی‌مانده: ' . fa_digits($remaining) . ' دقیقه'
                    : 'بیرون از ساعت کاری',
                icon: $isOpen ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle',
                color: $isOpen ? 'success' : 'danger',
            ),
        ];
    }
}
```

### مثال ۵: نمایش برنامه هفتگی

```php
class SchedulePage extends \Filament\Pages\Page
{
    public function getScheduleProperty(): array
    {
        return \Sghazanfari\FilamentPersian\Support\WorkingHours::weeklySchedule();
    }
}
```

---

## فایل‌های داده

### `solar_holidays.php`

```php
// packages/sghazanfari/filament-persian/resources/data/solar_holidays.php
return [
    ['month' => 1, 'day' => 1, 'name' => 'جشن نوروز', 'is_holiday' => true],
    // ...
];
```

### `lunar_holidays.php`

```php
// packages/sghazanfari/filament-persian/resources/data/lunar_holidays.php
return [
    1403 => [
        ['date' => '2024-04-10', 'name' => 'عید سعید فطر'],
        // ...
    ],
    // ...
];
```

---

## به‌روزرسانی تعطیلات قمری

تاریخ‌های قمری هر سال تغییر می‌کنند. پکیج هر ۸ سال یک‌بار نیاز به به‌روزرسانی دارد.

### روش ۱: `composer update`

```bash
composer update sghazanfari/filament-persian
```

اگر نسخه جدیدتر با تعطیلات به‌روز منتشر شده باشد.

### روش ۲: ویرایش دستی

فایل `lunar_holidays.php` را باز کن و برای سال جدید اضافه کن:

```php
1411 => [
    ['date' => '2032-01-15', 'name' => 'عید سعید فطر'],
    // ...
],
```

### روش ۳: مشارکت در GitHub

اگر تعطیلات جدید را اضافه کردی، Pull Request بزن تا همه استفاده کنند.

---

## محدودیت‌ها

### دقت تعطیلات قمری

تاریخ‌های قمری در پکیج **تقریبی** هستند. ممکن است:

- یک روز جابه‌جایی داشته باشند (بسته به رؤیت هلال)
- تعطیلات دولتی خاص (مثل تعطیلی‌های مناسبتی) اضافه شوند

**راه‌حل حرفه‌ای:** استفاده از API سازمان هلال‌شناسی یا سرویس‌های تجاری. اگر لازم داری، در Issue بگو تا در نسخه‌های بعدی اضافه کنیم.

### تعطیلات خاص شرکتی

اگر شرکت تو تعطیلات خاص دارد (مثل تعطیلی آخر ماه یا تعطیلات داخلی)، فعلاً از پکیج پشتیبانی نمی‌شود. می‌توانی آن‌ها را در کد خودت اضافه کنی:

```php
// در ServiceProvider یا Middleware
\Sghazanfari\FilamentPersian\Support\WorkingHours::setConfig(
    array_merge(
        \Sghazanfari\FilamentPersian\Support\WorkingHours::config(),
        [/* تنظیمات سفارشی */]
    )
);
```

---

## نکات کاربردی

### کش و عملکرد

- تعطیلات در `static::$cache` کش می‌شوند
- `IranHolidays::clearCache()` بعد از تغییرات
- `WorkingHours::clearCache()` بعد از تغییر تنظیمات

### در فیلدهای Filament

اگر از `JalaliDatePicker` استفاده می‌کنی، می‌توانی تعطیلات را در تقویم رنگی کنی. این قابلیت در نسخه‌های بعدی اضافه می‌شود.

### در چارت‌ها

برای نمودارهایی که بر اساس روز یا ماه هستند، از `next_working_day` و `working_days_between` استفاده کن تا مقادیر غیرواقعی نمایش ندهند.

---

## سؤال‌های متداول

### آیا می‌توانم ساعت کاری را برای شعبه‌های مختلف جدا تنظیم کنم؟

فعلاً نه. تنظیمات سراسری است. اگر multi-tenant داری، بگو تا در Issue بررسی کنیم.

### چطور می‌فهمم امروز تعطیل است یا نه؟

```php
if (is_iran_holiday(now())) {
    echo 'امروز تعطیل است';
    $names = iran_holiday_names(now());
    echo 'به مناسبت: ' . implode('، ', $names);
}
```

### چطور روز کاری بعدی را با ساعت دقیق بگیرم؟

```php
$next = next_working_moment();
echo jalali_format($next, 'l j F Y H:i');
// شنبه ۱۲ مرداد ۱۴۰۳ ۰۸:۰۰
```

### آیا می‌توانم تعطیلات قمری سال بعد را اضافه کنم؟

بله، فایل `lunar_holidays.php` را ویرایش کن یا از GitHub آخرین نسخه را بگیر.

---

## در ادامه

- [تقویم جلالی](jalali-calendar)
- [کامپوننت‌ها](components)
- [داده‌های آماده ایران](iranian-data)