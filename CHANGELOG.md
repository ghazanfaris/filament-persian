# Changelog

All notable changes to `filament-persian` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---
## [1.0.2] - 2026-09-22

### Fixed
- **رندر Hook در Plugin**: حالا `FilamentPersianPlugin` به‌درستی JS تقویم و CSS را تزریق می‌کند
- **علت**: نسخه 1.0.1 در Packagist کد قدیمی داشت (tag re-tag blocked)

---

## [Unreleased]

### Planned
- قالب‌های صفحه ورود بیشتر
- گزارش‌ساز بصری
- Pivot Table
- API خودکار برای هر Resource
- Workflow و Approval
- Audit Log پیشرفته

---

## [1.0.0] - 2026-09-21

اولین نسخه رسمی.

### Added — تقویم جلالی
- تبدیل دوطرفه میلادی ↔ جلالی (الگوریتم Borkowski)
- کامپوننت فرم `JalaliDatePicker`
- ستون جدول `JalaliDateColumn`
- فیلتر بازه `JalaliDateFilter`
- Trait `AutoJalaliDates` برای تبدیل خودکار
- Trait `HasJalaliDates` برای مدل‌ها
- Macro `withJalaliTimestamps()` برای جدول
- تقویم JS مستقل (بدون وابستگی خارجی)
- Helper Functions: `jalali()`, `jalali_format()`, `to_jalali_carbon()`

### Added — اعداد و مبالغ
- ستون `PersianTextColumn`
- ستون `PersianMoneyColumn`
- ورودی `PersianNumberInput`
- اعداد فارسی خودکار در کل پنل
- Helper Functions: `fa_digits()`, `en_digits()`, `fa_number()`, `fa_money()`

### Added — اعتبارسنجی ایرانی (۱۱ مورد)
- `NationalCode` — کد ملی با checksum
- `Mobile` — موبایل با پشتیبانی از +98
- `Sheba` — شبا با الگوریتم IBAN
- `PostalCode` — کد پستی
- `BankCardNumber` — شماره کارت با تشخیص بانک
- `LegalEntityNationalId` — شناسه ملی حقوقی
- `EconomicCode` — شماره اقتصادی
- `VehiclePlate` — پلاک خودرو
- `SocialSecurityNumber` — شماره بیمه تأمین اجتماعی
- `StockExchangeCode` — کد بورسی
- `PassportNumber` — شماره گذرنامه

### Added — ورودی‌های ایرانی
- `IranianMobileInput`
- `IranianNationalCodeInput`
- `IranianShebaInput`
- `IranianBankCardInput`
- `IranianLegalEntityIdInput`
- `IranianEconomicCodeInput`
- `IranianVehiclePlateInput`
- `IranianSocialSecurityInput`
- `IranianStockExchangeCodeInput`
- `IranianPassportInput`

### Added — داده‌های آماده ایران
- ۳۱ استان با مرکز و اسلاگ
- ۳۱۵ شهر مهم
- ۳۰ بانک با پیشوند کارت و کد شبا
- ۲۲ منطقه شهرداری تهران
- `IranProvinceSelect`, `IranCitySelect`, `IranBankSelect`
- Command `filament-persian:seed-cities`
- جدول `iran_cities`
- مدل `IranCity` با scopes

### Added — تعطیلات و ساعت کاری
- تعطیلات شمسی ثابت
- تعطیلات قمری برای ۱۴۰۳-۱۴۱۰
- تعطیلات هفتگی (جمعه)
- ساعت کاری اداری قابل تنظیم
- محاسبه روز کاری بعدی/قبلی
- تعداد روزهای کاری بین دو تاریخ
- Helper Functions کامل

### Added — جستجو
- Trait `HasPersianGlobalSearch` با نرمال‌سازی ی/ک
- Command Palette با `Ctrl+K`
- Endpoint `/admin/_command-search`

### Added — ویجت و نمودار
- کلاس انتزاعی `PersianStatsWidget` با ۶ متد
- کلاس انتزاعی `PersianChartWidget` با محور جلالی
- متدهای کمکی برای تاریخ و اعداد

### Added — صفحه‌ها
- `Documentation` — ۱۳ فایل مستندات داخلی
- `ComponentsShowcase` — نمایش ۳۰+ کامپوننت با کد
- `About` — معرفی کامل پکیج
- `FontSettings` — تنظیمات ظاهری با ۶ تب
- `EditProfile` — پروفایل کاربر با آواتار

### Added — صفحه ورود
- کلاس `Login` اختصاصی
- ۱۰ قالب: classic, minimal, split, glass, gradient, dark, neon, corporate, wave, pattern
- ویرایش متن‌ها از پنل
- پشتیبانی از 2FA

### Added — ظاهر و UX
- RTL کامل با CSS سراسری
- ۵ فونت فارسی: Vazirmatn, Estedad, Sahel, Samim, Shabnam
- ۲۰ رنگ آماده
- ۴ قالب آماده برای تنظیمات سریع
- Command Palette فارسی

### Added — زیرساخت
- `FilamentPersianPlugin` برای ثبت خودکار
- Command `filament-persian:install`
- Command `filament-persian:doctor`
- Command `filament-persian:seed-cities`
- Helper Functions (۵۴ تابع)
- فایل‌های مستندات (۱۳ فایل)

### Supported
- PHP 8.2+
- Laravel 11, 12, 13
- Filament 5.x

---

## [0.0.1] - 2026-09-20

### Added
- نسخه اولیه توسعه
- تست هسته تبدیل تاریخ جلالی

---

[Unreleased]: https://github.com/ghazanfaris/filament-persian/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/ghazanfaris/filament-persian/releases/tag/v1.0.0
[0.0.1]: https://github.com/ghazanfaris/filament-persian/releases/tag/v0.0.1