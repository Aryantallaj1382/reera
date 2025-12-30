@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    🏙 شهرهای {{ $country->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    مدیریت شهرهای مربوط به این کشور
                </p>
            </div>

            <a href="{{ route('countries.index') }}"
               class="text-sm text-blue-600 hover:underline">
                ← بازگشت به کشورها
            </a>
        </div>

        {{-- Add City Card --}}
        <div class="bg-white shadow-lg rounded-2xl p-5 mb-8">
            <form method="POST" action="{{ route('cities.store', $country) }}" class="flex gap-3">
                @csrf
                <input
                    name="name"
                    placeholder="نام شهر را وارد کنید"
                    class="flex-1 border border-gray-300 rounded-xl px-4 py-2
                       focus:ring-2 focus:ring-green-500 focus:outline-none"
                >
                <button
                    class="bg-green-600 hover:bg-green-700 transition
                       text-white px-6 py-2 rounded-xl font-medium">
                    ➕ افزودن
                </button>
            </form>
        </div>

        {{-- Cities List --}}
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <ul class="divide-y">
                @forelse($cities as $city)
                    <li class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                        <span class="w-9 h-9 flex items-center justify-center
                                     bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            {{ $loop->iteration }}
                        </span>
                            <span class="font-medium text-gray-800">
                            {{ $city->name }}
                        </span>
                        </div>

                        <form method="POST" action="{{ route('cities.destroy', $city) }}"
                              onsubmit="return confirm('این شهر حذف شود؟')">
                            @csrf
                            @method('DELETE')
                            <button
                                class="px-3 py-1 rounded-lg bg-red-100 text-red-700
                                   hover:bg-red-200 transition text-xs">
                                🗑 حذف
                            </button>
                        </form>
                    </li>
                @empty
                    <li class="p-6 text-center text-gray-500">
                        🚫 شهری برای این کشور ثبت نشده است
                    </li>
                @endforelse
            </ul>
        </div>

    </div>
@endsection
