<h3 class="text-lg font-semibold mb-3">
    اطلاعات آگهی دیجیتال
</h3>

<div class="grid grid-cols-4 gap-4 text-sm text-gray-700">

    <div>
        <span class="font-medium">قوانین:</span>
        @if($ad->rules && count($ad->rules) > 0)
            <ul class="list-disc list-inside mt-2 space-y-1 text-gray-700">
                @foreach($ad->rules as $rule)
                    <li>{{ $rule }}</li>
                @endforeach
            </ul>
        @else
            <span class="text-gray-500">-</span>
        @endif
    </div>
    <div>
        <span class="font-medium">stage:</span>
        {{ $ad->stage ?? '-' }}
    </div>
    <div>
        <span class="font-medium">قیمت:</span>
        {{ $ad->price  ?? '-' }}
    </div>
    <div>
        <span class="font-medium">ارز:</span>
        {{ $ad->currency?->title ?? '-' }}
    </div>


</div>
