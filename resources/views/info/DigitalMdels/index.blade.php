<!-- resources/views/models/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6">

        <h2 class="text-2xl font-bold mb-4">مدل‌های برند: {{ $brand->name }}</h2>

        {{-- فرم اضافه کردن مدل --}}
        <form method="POST" action="{{ route('DigitalBrands.models.store', $brand) }}" class="flex gap-2 mb-6">
            @csrf
            <input name="name" placeholder="نام مدل"
                   class="border rounded px-3 py-2 w-full">
            <button class="bg-green-600 text-white px-4 rounded">افزودن مدل</button>
        </form>

        {{-- لیست مدل‌ها --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($models as $model)
                <div class="border rounded-xl p-4 flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition">
                    <span>{{ $model->name }}</span>
                    <form method="POST" action="{{ route('DigitalBrands.models.destroy', [$brand, $model]) }}"
                          onsubmit="return confirm('حذف شود؟')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 text-sm">🗑</button>
                    </form>
                </div>
            @endforeach
        </div>

    </div>
@endsection
