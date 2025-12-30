<h3 class="text-lg font-semibold mb-3">
    اطلاعات آگهی دیجیتال
</h3>

<div class="grid grid-cols-4 gap-4 text-sm text-gray-700">

    <div>
        <span class="font-medium">قوانین:</span>
        {{ $ad->role ?? '-' }}
    </div>
    <div>
        <span class="font-medium">مدرک:</span>
        {{ $ad->degree ?? '-' }}
    </div>
    <div>
        <span class="font-medium">دسته ی کاری:</span>
        {{ $ad->category->name ?? '-' }}
    </div>

    <div>
        <span class="font-medium">مهارت ها:</span>
        {{ $ad->skill ?? '-' }}
    </div>
    <div>
        <span class="font-medium">وضعیت:</span>
        {{ $ad->price  ?? '-' }}
    </div>
    <div>
        <span class="font-medium">ساعت مشاهده:</span>
        {{ $ad->currency?->title ?? '-' }}
    </div>


</div>
