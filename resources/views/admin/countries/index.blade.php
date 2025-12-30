@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                🌍 مدیریت کشورها
            </h1>
        </div>

        {{-- Add Country Card --}}
        <div class="bg-white shadow-lg rounded-2xl p-5 mb-8">
            <form method="POST" action="{{ route('countries.store') }}" class="flex gap-3">
                @csrf
                <input
                    name="name"
                    placeholder="نام کشور را وارد کنید"
                    class="flex-1 border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                >
                <button
                    class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-2 rounded-xl font-medium">
                    ➕ افزودن
                </button>
            </form>
        </div>

        {{-- Countries Table --}}
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-4 text-right">کشور</th>
                    <th class="p-4 text-center">تعداد شهر</th>
                    <th class="p-4 text-center">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($countries as $country)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-medium text-gray-800">
                            {{ $country->name }}
                        </td>

                        <td class="p-4 text-center">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                                {{ $country->cities_count }} شهر
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <a
                                    href="{{ route('cities.show', $country) }}"
                                    class="px-3 py-1 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition text-xs">
                                    🏙 شهرها
                                </a>

                                <form method="POST" action="{{ route('countries.destroy', $country) }}"
                                      onsubmit="return confirm('حذف شود؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="px-3 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition text-xs">
                                        🗑 حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-500">
                            🚫 کشوری ثبت نشده است
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection

