<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>{{$user->name}} - رزومه</title>

    <!-- فونت فارسی Vazirmatn از CDN (رسمی) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@33.003/Vazirmatn-wdth-variable-font-face.css">

    <!-- برای اطمینان از لود شدن فونت در PDF (wkhtmltopdf / DomPDF) -->
    <style>
        @font-face {
            font-family: 'Vazirmatn';
            src: url('https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@33.003/Vazirmatn-wdth[wght].woff2') format('woff2');
            font-weight: 100 900;
            font-display: swap;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Vazirmatn', sans-serif;
            direction: rtl;
            text-align: right;
            line-height: 1.8;
            font-size: 14px;
            background: #f3f4f6;
            color: #1f2937;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 45px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            font-feature-settings: "ss01", "cv01";
        }

        .header {
            display: flex;
            align-items: center;
            gap: 28px;
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 4px solid #1e40af;
        }

        .header img {
            width: 115px;
            height: 115px;
            border-radius: 50%;
            border: 5px solid #1e40af;
            object-fit: cover;
            flex-shrink: 0;
        }

        .header h1 {
            font-size: 30px;
            font-weight: 700;
            color: #1e40af;
            margin: 0;
        }

        .header p {
            margin: 6px 0;
            color: #4b5563;
            font-size: 15px;
        }

        .section {
            margin-bottom: 32px;
        }

        .section h2 {
            font-size: 22px;
            font-weight: 600;
            color: #1e40af;
            margin: 0 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #dbeafe;
            position: relative;
        }

        .item {
            position: relative;
            padding-right: 25px;
            margin-bottom: 22px;
        }

        .item::before {
            content: '';
            position: absolute;
            right: 0;
            top: 12px;
            width: 10px;
            height: 10px;
            background: #1e40af;
            border-radius: 50%;
        }

        .item h3 {
            font-size: 17px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 5px 0;
        }

        .item p {
            margin: 0;
            font-size: 14px;
            color: #6b7280;
        }

        .item p.desc {
            margin-top: 8px;
            color: #374151;
            font-size: 13.5px;
            line-height: 1.9;
        }

        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 12px;
        }

        .skill {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* برای PDF: اطمینان از لود شدن فونت در wkhtmltopdf / DomPDF */
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .container { box-shadow: none; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        @if($user->photo && file_exists(public_path('storage/' . $user->photo)))
            <img src="{{ public_path('storage/' . $user->photo) }}" alt="عکس پروفایل">
        @else
            <div style="width:115px;height:115px;background:#e5e7eb;border-radius:50%;border:5px solid #1e40af;"></div>
        @endif
        <div>
            <h1>{{ $user->name }}</h1>
            <p>سن: {{ $user->age }}</p>
            <p>موبایل: {{ $user->mobile }}</p>
        </div>
    </div>

    <!-- تحصیلات -->
    <div class="section">
        <h2>تحصیلات</h2>
        @forelse($educations as $edu)
            <div class="item">
                <h3>{{ $edu->major_en ?? $edu->major }} ({{ $edu->degree_en ?? $edu->degree }})</h3>
                <p>{{ $edu->university_name_en ?? $edu->university_name }} • {{ $edu->start_year }} - {{ $edu->is_current ? 'اکنون' : $edu->end_year }}</p>
                <p class="desc">{!! nl2br(e($edu->description_en ?? $edu->description)) !!}</p>
            </div>
        @empty
            <p style="color:#9ca3af;">هیچ مدرک تحصیلی ثبت نشده است.</p>
        @endforelse
    </div>

    <!-- سوابق کاری -->
    <div class="section">
        <h2>سوابق کاری</h2>
        @forelse($works as $work)
            <div class="item">
                <h3>{{ $work->title_en ?? $work->title }}</h3>
                <p>{{ $work->company_name_en ?? $work->company_name }} • {{ $work->start_year }} - {{ $work->is_current ? 'اکنون' : $work->end_year }}</p>
                <p class="desc">{!! nl2br(e($work->description_en ?? $work->description)) !!}</p>
            </div>
        @empty
            <p style="color:#9ca3af;">هیچ سابقه کاری ثبت نشده است.</p>
        @endforelse
    </div>

    <!-- مهارت‌ها -->
    <div class="section">
        <h2>مهارت‌ها</h2>
        <div class="skills">
            @forelse($skills as $skill)
                <span class="skill">{{ $skill->name_en ?? $skill->name }}</span>
            @empty
                <span style="color:#9ca3af;">مهارتی ثبت نشده است.</span>
            @endforelse
        </div>
    </div>
</div>
</body>
</html>
