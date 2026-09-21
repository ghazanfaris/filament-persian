# ویجت‌ها و نمودارها

## ویجت آمار

کلاس `PersianStatsWidget` یک کلاس انتزاعی است که متدهای فارسی‌سازی دارد.

### ساخت یک ویجت آمار

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
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
                chart: [10, 20, 15, 30, 45, 60, 80],
            ),

            $this->makeMoneyStat(
                label: 'درآمد کل',
                amount: Order::sum('total'),
                currency: 'IRT',
                description: 'ماه جاری',
                color: 'primary',
            ),

            $this->makeDateStat(
                label: 'امروز',
                date: now(),
                pattern: 'l j F Y',
                description: 'تاریخ شمسی',
                icon: 'heroicon-m-calendar-days',
                color: 'info',
            ),

            $this->makeHumanDateStat(
                label: 'آخرین سفارش',
                date: Order::latest()->first()?->created_at ?? now(),
                icon: 'heroicon-m-clock',
                color: 'gray',
            ),
        ];
    }
}
```

## متدهای موجود

| متد | ورودی | خروجی |
|---|---|---|
| `makeNumberStat` | `$label, $value, $description, $icon, $color, $chart, $thousands` | Stat عددی |
| `makeMoneyStat` | `$label, $amount, $currency, $description, $icon, $color, $decimals` | Stat مبلغ |
| `makePercentStat` | `$label, $percent, $description, $icon, $color, $decimals` | Stat درصد |
| `makeDateStat` | `$label, $date, $pattern, $description, $icon, $color` | Stat تاریخ جلالی |
| `makeHumanDateStat` | `$label, $date, $description, $icon, $color` | «۳ روز پیش» |
| `makeTextStat` | `$label, $value, $description, $icon, $color` | متن با اعداد فارسی |

## متدهای کمکی

| متد | توضیح |
|---|---|
| `formatDigits($value)` | ارقام فارسی |
| `formatNumber($value, $decimals, $thousands)` | عدد با جداکننده |
| `formatMoney($amount, $currency, $decimals)` | مبلغ |
| `growthDescription($current, $previous, $unit)` | «۱۲٪ رشد نسبت به ماه گذشته» |
| `growthColor($current, $previous)` | رنگ بر اساس رشد |
| `growthIcon($current, $previous)` | آیکون بر اساس رشد |

## مثال: رشد خودکار

```php
protected function getStats(): array
{
    $thisMonth = User::whereMonth('created_at', now()->month)->count();
    $lastMonth = User::whereMonth('created_at', now()->subMonth()->month)->count();

    return [
        $this->makeNumberStat(
            label: 'کاربران این ماه',
            value: $thisMonth,
            description: $this->growthDescription($thisMonth, $lastMonth),
            icon: $this->growthIcon($thisMonth, $lastMonth),
            color: $this->growthColor($thisMonth, $lastMonth),
        ),
    ];
}
```

اگر `$thisMonth > $lastMonth` باشد:

- متن: «۱۲٪ رشد نسبت به ماه گذشته»
- رنگ: `success` (سبز)
- آیکون: `arrow-trending-up`

## نمودار با محور جلالی

کلاس `PersianChartWidget` برای ساخت نمودارهایی که محور X آن‌ها تاریخ شمسی است.

### ساخت یک نمودار

```php
<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Sghazanfari\FilamentPersian\Widgets\PersianChartWidget;

class OrdersChart extends PersianChartWidget
{
    protected ?string $heading = 'سفارش‌ها در ۳۰ روز اخیر';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // گرفتن ۳۰ روز اخیر با برچسب شمسی
        [$labels, $dates] = $this->lastNDays(30);

        // محاسبه مقدار برای هر روز
        $counts = [];
        foreach ($dates as $date) {
            $counts[] = Order::whereDate('created_at', $date->toDateString())->count();
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
        return 'line';  // line, bar, doughnut, pie, radar
    }
}
```

### متدهای کمکی

| متد | توضیح |
|---|---|
| `lastNDays(30)` | ۳۰ روز اخیر با برچسب `m/d` |
| `lastNMonths(12)` | ۱۲ ماه اخیر |
| `lastWeek()` | ۷ روز اخیر با نام روز (`D j M`) |
| `makeDataset($label, $dates, $values, $colorIndex)` | dataset خطی |
| `makeBarDataset($label, $values, $colorIndex)` | dataset ستونی |
| `getJalaliLabels($dates)` | تبدیل آرایه تاریخ به برچسب جلالی |

### نمودار ستونی

```php
protected function getData(): array
{
    [$labels, $dates] = $this->lastNMonths(12);

    $revenues = [];
    foreach ($dates as $date) {
        $revenues[] = Order::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->sum('total');
    }

    return [
        'datasets' => [
            $this->makeBarDataset('درآمد ماهانه', $revenues, 2),
        ],
        'labels' => $labels,
    ];
}

protected function getType(): string
{
    return 'bar';
}
```

### چند سری در یک نمودار

```php
protected function getData(): array
{
    [$labels, $dates] = $this->lastNDays(30);

    $orders = [];
    $carts = [];
    foreach ($dates as $date) {
        $orders[] = Order::whereDate('created_at', $date)->count();
        $carts[] = Cart::whereDate('created_at', $date)->count();
    }

    return [
        'datasets' => [
            $this->makeDataset('سفارش‌ها', $dates, $orders, 0),
            $this->makeDataset('سبدهای خرید', $dates, $carts, 1),
        ],
        'labels' => $labels,
    ];
}
```

## رنگ‌ها

پالت پیش‌فرض:

```php
protected array $palette = [
    '#0ea5e9', // آبی
    '#8b5cf6', // بنفش
    '#10b981', // سبز
    '#f43f5e', // سرخ
    '#f59e0b', // کهربایی
    '#3b82f6', // آبی تیره
    '#ec4899', // صورتی
    '#14b8a6', // فیروزه‌ای
];
```

برای تغییر، در ویجت خودت `$palette` را override کن:

```php
protected array $palette = [
    '#2563eb',
    '#16a34a',
    '#dc2626',
];
```

## فونت و RTL

نمودار به‌طور خودکار:

- فونت را از `config('filament-persian.font.family')` می‌خواند
- RTL فعال می‌کند
- Tooltip فارسی
- اعداد محور Y به فارسی

## ثبت ویجت‌ها در پنل

در `app/Providers/Filament/AdminPanelProvider.php`:

```php
->widgets([
    \App\Filament\Widgets\DashboardStats::class,
    \App\Filament\Widgets\OrdersChart::class,
])
```

## چیدمان

`$columnSpan` تعیین می‌کند ویجت چقدر عرض بگیرد:

```php
protected int|string|array $columnSpan = 1;      // نصف عرض
protected int|string|array $columnSpan = 2;      // تمام عرض
protected int|string|array $columnSpan = 'full'; // تمام عرض (پیشنهادی برای نمودار)
```

## مثال پیشرفته: نمودار با فیلتر بازه

```php
class RevenueChart extends PersianChartWidget
{
    protected ?string $heading = 'درآمد';
    protected int|string|array $columnSpan = 'full';

    public ?string $range = '30d';

    protected function getData(): array
    {
        [$labels, $dates] = match ($this->range) {
            '7d' => $this->lastNDays(7),
            '30d' => $this->lastNDays(30),
            '12m' => $this->lastNMonths(12),
            default => $this->lastNDays(30),
        };

        $revenues = [];
        foreach ($dates as $date) {
            $revenues[] = Order::whereDate('created_at', $date)->sum('total');
        }

        return [
            'datasets' => [
                $this->makeDataset('درآمد', $dates, $revenues, 0),
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function selectRange(string $range): void
    {
        $this->range = $range;
    }
}
```