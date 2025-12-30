@php
    $level = $level ?? 0;

    $bgColors = [
        'bg-white',
        'bg-gray-50',
        'bg-blue-50',
        'bg-green-50',
        'bg-yellow-50',
        'bg-purple-50',
    ];

    $borderColors = [
        'border-gray-300',
        'border-blue-300',
        'border-green-300',
        'border-yellow-300',
        'border-purple-300',
    ];

    $bg = $bgColors[$level % count($bgColors)];
    $border = $borderColors[$level % count($borderColors)];
@endphp

<div class="border-r-4 {{ $border }} {{ $bg }} rounded-xl p-4 transition">

    <div class="flex justify-between items-center">
        <div>
            <p class="font-medium text-gray-800">
                {{ $category->title }}
            </p>
            <p class="text-xs text-gray-500">
                {{ $category->title_en }}
            </p>
        </div>

        <div class="flex gap-2 text-xs">
            {{-- افزودن زیر دسته --}}
            <form method="POST"
                  action="{{ route('categories.children.store', $category) }}"
                  class="flex gap-1">
                @csrf
                <input name="title" placeholder="FA"
                       class="border rounded px-2 py-1 w-20 text-xs bg-white">
                <input name="title_en" placeholder="EN"
                       class="border rounded px-2 py-1 w-20 text-xs bg-white">
                <button class="text-green-600 hover:scale-110 transition">
                    ➕
                </button>
            </form>

            {{-- حذف --}}
            <form method="POST"
                  action="{{ route('categories.destroy', $category) }}"
                  onsubmit="return confirm('حذف شود؟')">
                @csrf @method('DELETE')
                <button class="text-red-600 hover:scale-110 transition">
                    🗑
                </button>
            </form>
        </div>
    </div>

    {{-- children --}}
    @if($category->children->count())
        <div class="mt-3 ml-6 space-y-2">
            @foreach($category->children as $child)
                @include('admin.categories.category-item', [
                    'category' => $child,
                    'level' => $level + 1
                ])
            @endforeach
        </div>
    @endif
</div>
