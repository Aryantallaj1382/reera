    <!DOCTYPE html>
    <html lang="fa">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0;">
        <title>پنل مدیریت | نت کپی</title>

        <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/responsive_991.css') }}" media="(max-width:991px)">
        <link rel="stylesheet" href="{{ asset('admin/css/responsive_768.css') }}" media="(max-width:768px)">
        <link rel="stylesheet" href="{{ asset('admin/css/font.css') }}">
    </head>
    <body>

    @include('admin.layouts.sidebar')

    <div class="content">
        @include('admin.layouts.heder')

        <div class="breadcrumb">
            <ul>
                <li><a href="#" title="پیشخوان">پیشخوان</a></li>
            </ul>
        </div>

        <div class="main-content">
            @yield('content')
        </div>

        @include('admin.layouts.footer')
    </div>

    <script src="{{ asset('admin/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('admin/js/js.js') }}"></script>
    </body>
    </html>
