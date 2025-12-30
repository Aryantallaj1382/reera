@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto p-6">

        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-bold">
                📁 {{ $category->title }}
            </h2>

            <a href="{{ route('categories.index') }}"
               class="text-blue-600 hover:underline text-sm">
                ← بازگشت
            </a>
        </div>

        {{-- افزودن زیر دسته --}}
        <div class="bg-white p-4 rounded-xl shadow mb-6">
            <form method="POST" action="{{ route('categories.children.store', $category) }}"
                  class="flex gap-3">
                @csrf
                <input name="title"
                       placeholder="نام فارسی"
                       class="flex-1 border rounded-xl px-3 py-2">

                <input name="title_en"
                       placeholder="English name"
                       class="flex-1 border rounded-xl px-3 py-2">

                <button class="bg-green-600 text-white px-5 rounded-xl">
                    ➕ افزودن
                </button>
            </form>

        </div>

        {{-- لیست زیر دسته‌ها --}}
        <div class="space-y-3">
            @foreach($category->children as $child)
                @include('admin.categories.category-item', [
        'category' => $child,
        'level' => 1
    ])
            @endforeach
        </div>

    </div>
@endsection
