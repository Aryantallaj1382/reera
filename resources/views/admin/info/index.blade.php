@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                🧭 بخش‌های مدیریتی
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                برای ورود به هر بخش از کارت‌ها استفاده کنید
            </p>
        </div>

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            {{-- Card 1 --}}
            <a href="{{ route('DigitalBrands.index') }}" >
            <div
                class="group cursor-pointer rounded-2xl p-6 text-white shadow-lg
                   bg-gradient-to-br from-blue-500 to-blue-600
                   hover:scale-[1.03] transition">
                <h2 class="text-lg font-bold mb-2 text-center">
                    برند های دیجیتال
                </h2>

            </div>
            </a>
            {{-- Card 2 --}}
            <a href="{{ route('KitchenBrand.index') }}" >

            <div
                class="group cursor-pointer rounded-2xl p-6 text-white shadow-lg
                   bg-gradient-to-br from-green-500 to-green-600
                   hover:scale-[1.03] transition">
                <h2 class="text-lg font-bold mb-2 text-center">
                    برند های آشپزخانه
                </h2>

            </div>
            </a>

            {{-- Card 3 --}}
            <a href="{{ route('VehicleBrand.index') }}" >

            <div
                class="group cursor-pointer rounded-2xl p-6 text-white shadow-lg
                   bg-gradient-to-br from-purple-500 to-purple-600
                   hover:scale-[1.03] transition">
                <h2 class="text-lg font-bold mb-2 text-center">
                  برند های خودرو
                </h2>

            </div>
            </a>
            {{-- Card 4 --}}
            <div
                class="group cursor-pointer rounded-2xl p-6 text-white shadow-lg
                   bg-gradient-to-br from-pink-500 to-pink-600
                   hover:scale-[1.03] transition">
                <h2 class="text-lg font-bold mb-2 text-center">
                    تنظیمات
                </h2>

            </div>

        </div>

    </div>
@endsection
