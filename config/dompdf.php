<?php

return [

    'show_warnings' => false,

    'public_path' => null,

    'convert_entities' => true,

    'options' => [

        // مسیر فونت‌ها
        'font_dir' => public_path('public/fonts'),
        'font_cache' => public_path('public/fonts'),
        // دایرکتوری موقت
        'temp_dir' => sys_get_temp_dir(),

        // محدود کردن دسترسی به فایل‌های سیستم
        'chroot' => realpath(base_path()),

        // پروتکل‌های مجاز
        'allowed_protocols' => [
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        // فعال کردن زیرمجموعه فونت (حجم کمتر)
        'enable_font_subsetting' => true,

        // موتور رندر (CPDF بهترین برای فارسی)
        'pdf_backend' => 'CPDF',

        // نوع رسانه
        'default_media_type' => 'print',

        // اندازه کاغذ
        'default_paper_size' => 'a4',
        'default_paper_orientation' => 'portrait',

        // فونت پیش‌فرض (مهم!)
        'default_font' => 'Vazirmatn',

        // DPI (96 استاندارد مرورگرها)
        'dpi' => 96,

        // غیرفعال کردن PHP در PDF (امنیت)
        'enable_php' => false,

        // فعال کردن جاوااسکریپت (برای فرم‌ها و انیمیشن در PDF)
        'enable_javascript' => true,

        // فعال کردن دسترسی به تصاویر خارجی (مثل placeholder)
        'enable_remote' => true,

        // فقط از دامنه‌های خاص (اختیاری - امنیت بیشتر)
        'allowed_remote_hosts' => null,

        // نسبت ارتفاع فونت (خوانایی بهتر)
        'font_height_ratio' => 1.2,

        // HTML5 Parser (حتماً فعال)
        'enable_html5_parser' => true,

        // مهم: فعال کردن رندر تصاویر خارجی
        'is_remote_enabled' => true,

        // فعال کردن فونت‌های خارجی
        'is_font_subsetting_enabled' => true,
    ],

];
