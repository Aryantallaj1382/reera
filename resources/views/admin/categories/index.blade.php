@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6">

        <h1 class="text-2xl font-bold mb-6">📂 دسته‌بندی‌های اصلی</h1>

        <div class="bg-white shadow rounded-2xl divide-y">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}"
                   class="block p-4 hover:bg-gray-50 transition font-medium">
                    {{ $category->title }}
                </a>
            @endforeach
        </div>

    </div>
@endsection
