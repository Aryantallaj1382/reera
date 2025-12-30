<!-- resources/views/brands/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6">

        <h2 class="text-2xl font-bold mb-4">برندها</h2>

        {{-- فرم اضافه کردن --}}
        <form method="POST" action="{{ route('VehicleBrand.store') }}" class="flex gap-2 mb-6">
            @csrf
            <input name="name" placeholder="نام برند"
                   class="border rounded px-3 py-2 w-full">
            <button class="bg-blue-600 text-white px-4 rounded">افزودن</button>
        </form>

        {{-- لیست برندها --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($brands as $brand)
                <a href="{{route('VehicleBrand.models.index' , $brand)}}">
                    <div class="border rounded-xl p-4 flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition">
                        <span>{{ $brand->name }}</span>
                        <form method="POST" action="{{ route('VehicleBrand.destroy', $brand) }}" onsubmit="return confirm('حذف شود؟')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-sm">🗑</button>
                        </form>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
@endsection
