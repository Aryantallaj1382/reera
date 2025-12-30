<h3 class="text-lg font-semibold mb-3">
    اطلاعات آگهی دیجیتال
</h3>

<div class="grid grid-cols-4 gap-4 text-sm text-gray-700">

    <div>
        <span class="font-medium">برند:</span>
        {{ $ad->brand->name ?? '-' }}
    </div>

    <div>
        <span class="font-medium">مدل:</span>
        {{ $ad->type->name ?? '-' }}
    </div>
    <div>
        <span class="font-medium">وضعیت:</span>
        {{ $ad->condition_fa  ?? '-' }}
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
