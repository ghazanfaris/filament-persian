# جستجوی فارسی

## مشکل

در دیتابیس ممکن است `کریمی` (فارسی) ذخیره شده باشد، ولی کاربر `كريمي` (عربی) تایپ کند. جستجوی پیش‌فرض Filament این تفاوت را تشخیص نمی‌دهد.

همچنین:

- `ی` فارسی vs `ي` عربی
- `ک` فارسی vs `ك` عربی
- `ة` عربی vs `ه` فارسی
- `ۀ` vs `ه`
- نیم‌فاصله vs فاصله معمولی
- ارقام فارسی vs لاتین

## راه‌حل

پکیج این نرمال‌سازی را انجام می‌دهد:

| از | به |
|---|---|
| `ي` | `ی` |
| `ك` | `ک` |
| `ة` | `ه` |
| `ۀ` | `ه` |
| `ؤ` | `و` |
| `إ` | `ا` |
| `أ` | `ا` |
| `٠-٩` | `0-9` |
| نیم‌فاصله | حذف |

## استفاده در Resource

```php
use Sghazanfari\FilamentPersian\Concerns\HasPersianGlobalSearch;

class EventResource extends Resource
{
    use HasPersianGlobalSearch;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description', 'body'];
    }
}
```

## نتیجه

وقتی کاربر `Ctrl+K` می‌زند و `كریمی` تایپ می‌کند:

- **قبل:** هیچ نتیجه‌ای (چون `ی` با `ي` فرق دارد)
- **بعد:** همه نتایجی که `کریمی` (فارسی) در دیتابیس دارند

همچنین برعکس: اگر دیتابیس `كريمي` داشته باشد و کاربر `کریمی` تایپ کند، پیدا می‌شود.

## نحوه کار

پکیج یک `orWhere` با `REPLACE` تودرتو به query اضافه می‌کند:

```sql
WHERE (title LIKE '%كریمی%')  -- شرط پیش‌فرض Filament
   OR (REPLACE(REPLACE(REPLACE(title,
        'ي', 'ی'),
        'ك', 'ک'),
        CHAR(226, 128, 140), '')  -- نیم‌فاصله
       LIKE '%کریمی%')  -- شرط ما
```

## Command Palette

علاوه بر Global Search، یک Command Palette هم داریم که با `Ctrl+K` باز می‌شود.

### تفاوت با Global Search Filament

| Global Search Filament | Command Palette پکیج |
|---|---|
| فقط رکوردها | منوها + رکوردها |
| با Enter → پنل Filament | با Enter → صفحه |
| جستجوی ساده | جستجوی نرمال‌شده |

Command Palette:

- منوها و صفحات را از DOM سایدبار می‌خواند
- رکوردها را از یک endpoint (`/admin/_command-search`) می‌گیرد
- همه با نرمال‌سازی فارسی

### تست

1. `Ctrl+K` بزن
2. تایپ کن `رويداد` (با `ي` عربی)
3. نتیجه می‌آید (هم منو، هم رکوردها)

## محدودیت‌ها

### روابط (Relations)

جستجو در روابط (مثل `user.name`) هنوز پشتیبانی نمی‌شود. اگر لازم داری:

```php
public static function getGloballySearchableAttributes(): array
{
    return ['title', 'user.name'];  // ← این کار نمی‌کند هنوز
}
```

### JSON Columns

اگر ستون JSON داری، ممکن است `REPLACE` روی آن خطا بدهد. برای آن‌ها:

```php
public static function getGloballySearchableAttributes(): array
{
    return ['title'];  // فقط ستون‌های متن ساده
}
```

### عملکرد

هر `REPLACE` تودرتو روی دیتابیس، کمی کند است. برای جداول بزرگ (۱۰۰ هزار+ رکورد):

- **بهترین راه:** یک ستون `title_normalized` بساز
- **راه ساده:** در زمان save، متن را نرمال کن و ذخیره کن
- **راه سریع:** ایندکس `FULLTEXT` روی ستون نرمال‌شده

مثال:

```php
// Migration
Schema::table('events', function (Blueprint $table) {
    $table->text('title_normalized')->nullable();
    $table->fullText('title_normalized');
});

// Model
protected static function booted(): void
{
    static::saving(function ($model) {
        $model->title_normalized = app(PersianString::class)
            ->normalizeForSearch($model->title);
    });
}

// Resource
public static function getGloballySearchableAttributes(): array
{
    return ['title_normalized'];
}
```

## Helper Functions

```php
use Sghazanfari\FilamentPersian\Support\PersianString;

$string = app(PersianString::class);

// نرمال‌سازی ساده
$string->normalize('كريمي');          // کریمی

// نرمال‌سازی برای جستجو (حذف نیم‌فاصله، فشرده‌سازی فاصله)
$string->normalizeForSearch('كريمي');  // کریمی

// بررسی contains
$string->contains('علی کریمی', 'كريمي');  // true

// ساخت SQL expression
$string->sqlNormalize('events.title');
```

## فعال/غیرفعال کردن

می‌توانی جستجوی فارسی را برای یک Resource غیرفعال کنی:

```php
class SimpleResource extends Resource
{
    // use HasPersianGlobalSearch;  ← کامنت کن
}
```

## نکته: نسخه v5

در Filament v5، `modifyGlobalSearchQuery` به‌جای `Builder` `void` برمی‌گرداند و query را in-place تغییر می‌دهد. پکیج با این API سازگار است.