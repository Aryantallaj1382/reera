<h3 class="text-lg font-semibold mb-3">
    اطلاعات آگهی دیجیتال
</h3>

<div class="grid grid-cols-4 gap-4 text-sm text-gray-700">

    <div>
        <span class="font-medium">نوع:</span>
        {{ $ad->expertise->name ?? '-' }}
    </div>

    <div>
        <span class="font-medium">توضیحات:</span>
        {{ $ad->text ?? '-' }}
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
