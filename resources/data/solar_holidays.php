<?php

/**
 * تعطیلات رسمی شمسی ایران (ثابت هر سال).
 * فرمت: [month, day] => نام مناسبت
 *
 * نکته: روزهای "تعطیل رسمی" با is_holiday=true مشخص شده‌اند.
 * مناسبت‌های غیرتعطیل با is_holiday=false فقط برای نمایش در تقویم هستند.
 */
return [
    // فروردین
    ['month' => 1,  'day' => 1,  'name' => 'جشن نوروز',                'is_holiday' => true],
    ['month' => 1,  'day' => 2,  'name' => 'عید نوروز',                'is_holiday' => true],
    ['month' => 1,  'day' => 3,  'name' => 'عید نوروز',                'is_holiday' => true],
    ['month' => 1,  'day' => 4,  'name' => 'عید نوروز',                'is_holiday' => true],
    ['month' => 1,  'day' => 12, 'name' => 'روز جمهوری اسلامی',       'is_holiday' => true],
    ['month' => 1,  'day' => 13, 'name' => 'روز طبیعت (سیزده‌بدر)',   'is_holiday' => true],

    // خرداد
    ['month' => 3,  'day' => 14, 'name' => 'رحلت حضرت امام خمینی',   'is_holiday' => true],
    ['month' => 3,  'day' => 15, 'name' => 'قیام ۱۵ خرداد',           'is_holiday' => true],

    // بهمن
    ['month' => 11, 'day' => 22, 'name' => 'پیروزی انقلاب اسلامی',    'is_holiday' => true],

    // اسفند
    ['month' => 12, 'day' => 29, 'name' => 'روز ملی شدن صنعت نفت',    'is_holiday' => true],
];