<h3 class="text-lg font-semibold mb-3">
    اطلاعات آگهی دیجیتال
</h3>

<div class="grid grid-cols-4 gap-4 text-sm text-gray-700">

    <div>
        <span class="font-medium">مساحت:</span>
        {{ $ad->area?? '-' }}
    </div>

    <div>
        <span class="font-medium">مدل:</span>
        {{ $ad->type->name ?? '-' }}
    </div>
    <div>
        <span class="font-medium">سال ساخت:</span>
        {{ $ad->year  ?? '-' }}
    </div>

     <div>
        <span class="font-medium">تعداد اتاق:</span>
        {{ $ad->number_of_bedrooms  ?? '-' }}
    </div>

     <div>
        <span class="font-medium">تعداد سرویس:</span>
        {{ $ad->number_of_bathroom  ?? '-' }}
    </div>

     <div>
        <span class="font-medium">توضیحات:</span>
        {{ $ad->text  ?? '-' }}
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
