# تغییرات نسخه‌ها

## نسخه ۱.۰.۰ — ۱۴۰۵/۰۶/۳۰

اولین نسخه رسمی. شامل:

### تقویم جلالی

- تبدیل دوطرفه میلادی ↔ جلالی (الگوریتم Borkowski)
- کامپوننت `JalaliDatePicker` برای فرم‌ها
- ستون `JalaliDateColumn` برای جداول
- فیلتر `JalaliDateFilter` برای بازه
- Trait `AutoJalaliDates` برای تبدیل خودکار
- Macro `withJalaliTimestamps()` برای جداول
- Trait `HasJalaliDates` برای مدل‌ها
- تقویم JS حرفه‌ای (بدون وابستگی خارجی)

### اعداد و مبالغ

- ستون `PersianTextColumn` با اعداد فارسی
- ستون `PersianMoneyColumn` با فرمت پول
- ورودی `PersianNumberInput` با فرمت خودکار
- اعداد فارسی خودکار در کل پنل
- Helper Functions: `fa_digits()`, `en_digits()`, `fa_number()`, `fa_money()`

### اعتبارسنجی ایرانی

- `NationalCode` — کد ملی با checksum
- `Mobile` — موبایل با پشتیبانی از +98
- `Sheba` — شبا با الگوریتم IBAN
- `PostalCode` — کد پستی
- ورودی‌های آماده: `IranianMobileInput`, `IranianNationalCodeInput`, `IranianShebaInput`

### جستجو

- Trait `HasPersianGlobalSearch` با نرمال‌سازی ی/ک
- Command Palette فارسی با `Ctrl+K`
- Endpoint `/admin/_command-search`

### ویجت‌ها

- کلاس انتزاعی `PersianStatsWidget` برای آمار
- کلاس انتزاعی `PersianChartWidget` برای نمودار با محور جلالی
- متدهای کمکی برای رشد، تاریخ، پول

### صفحه‌ها

- `FontSettings` — تنظیمات ظاهری با ۴ تب
- `EditProfile` — پروفایل کاربر با آواتار
- `Documentation` — مستندات داخلی
- `About` — معرفی پکیج

### ظاهر

- RTL کامل با CSS سراسری
- ۵ فونت فارسی آماده
- ۲۰ رنگ
- ۴ قالب آماده

### زیرساخت

- `FilamentPersianPlugin` برای ثبت خودکار
- Command `filament-persian:install`
- Command `filament-persian:doctor`
- Helper Functions
- ترجمه‌های فارسی

### پشتیبانی

- PHP 8.2+
- Laravel 11, 12, 13
- Filament 4, 5

## نقشه راه

### نسخه ۱.۱ (آینده نزدیک)

- [ ] داده‌های آماده ایران (استان/شهر/بانک)
- [ ] اعتبارسنجی‌های بیشتر (شماره اقتصادی، شناسه ملی)
- [ ] صفحه لاگین فارسی با کپچا
- [ ] تعطیلات رسمی ایران
- [ ] ساعت کاری اداری

### نسخه ۱.۲ (میان‌مدت)

- [ ] گزارش‌ساز بصری
- [ ] Pivot Table
- [ ] KPI Widget با بازه شمسی
- [ ] Importer هوشمند Excel
- [ ] Webhook Builder

### نسخه ۲.۰ (بلندمدت)

- [ ] Multi-tenancy پیشرفته
- [ ] Workflow و Approval
- [ ] Audit Log پیشرفته
- [ ] API خودکار برای هر Resource
- [ ] Command Palette با میان‌برهای بیشتر

## چطور مشارکت کنم

1. Fork کن
2. Branch بساز (`feature/my-feature`)
3. Commit (`git commit -am 'Add feature'`)
4. Push (`git push origin feature/my-feature`)
5. Pull Request باز کن

## گزارش مشکل

اگر باگی پیدا کردی یا پیشنهادی داری:

- [GitHub Issues](https://github.com/ghazanfaris/filament-persian/issues)
- [GitHub Discussions](https://github.com/ghazanfaris/filament-persian/discussions)

## تشکر

- [Filament](https://filamentphp.com) برای فریم‌ورک عالی
- [Vazirmatn](https://github.com/rastikerdar/vazirmatn) برای فونت
- [Spatie](https://spatie.be) برای پکیج‌های بی‌نظیر
- و همه کسانی که باگ گزارش دادند یا مشارکت کردند