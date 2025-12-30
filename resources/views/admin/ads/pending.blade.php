@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-8">

        <h1 class="text-2xl font-bold mb-6">
            آگهی‌های در انتظار تأیید
        </h1>

        @if($ads->isEmpty())
            <div class="text-gray-500 text-center">
                آگهی‌ای برای بررسی وجود ندارد
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ads as $ad)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">

                    {{-- Image --}}
                    <img
                        src="{{ asset($ad->image) }}"
                        alt="{{ $ad->title }}"
                        class="w-full h-48 object-cover"
                    >

                    <div class="p-4 space-y-2">

                        {{-- Title --}}
                        <h2 class="text-lg font-semibold">
                            {{ $ad->title }}
                        </h2>

                        {{-- Category --}}
                        <div class="text-sm text-gray-600">
                            دسته‌بندی:
                            <span class="font-medium">
                            {{ $ad->category->title     ?? '---' }}
                        </span>
                        </div>

                        {{-- User --}}
                        <div class="text-sm text-gray-600">
                            ثبت‌کننده:
                            <span class="font-medium">
                            {{ $ad->user->name ?? '---' }}
                        </span>
                        </div>

                        {{-- Status --}}
                        <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                        در انتظار تأیید
                    </span>
                        <div class="mt-4">
                            <a
                                href="{{ route('admin.ads.show', $ad->id) }}"
                                class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm transition"
                            >
                                مشاهده
                            </a>
                        </div>


                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
