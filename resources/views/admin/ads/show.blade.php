@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-6 py-8">

        <a href="{{ route('admin.ads.pending') }}" class="text-sm text-blue-600 hover:underline">
            ← بازگشت
        </a>

        {{-- بخش دکمه‌های تایید و رد آگهی --}}
            <div class="mt-10 flex flex-wrap gap-4 justify-center">

                {{-- دکمه تایید - کوچک و زیبا --}}
                <form action="{{ route('admin.ads.approve', $ad) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300"
                            onclick="return confirm('آگهی «{{ Str::limit($ad->title, 30) }}» را تایید می‌کنید؟')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        تایید آگهی
                    </button>
                </form>

                {{-- دکمه رد - باز کردن مودال --}}
                <button type="button"
                        class="flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-red-300"
                        x-data
                        @click="$dispatch('open-modal', { id: 'reject-modal' })">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    رد آگهی
                </button>
            </div>

        {{-- نمایش دلیل رد اگر آگهی قبلاً رد شده باشد --}}
        @if($ad->status === 'rejected' && $ad->rejected_reason)
            <div class="mt-8 p-5 bg-red-50 border border-red-200 rounded-xl">
                <h4 class="font-bold text-red-800 mb-2">دلیل رد آگهی:</h4>
                <p class="text-red-700 leading-relaxed">{{ $ad->rejected_reason }}</p>
            </div>
        @endif

        {{-- مودال دلیل رد با Alpine.js (بدون Bootstrap) --}}
        <template x-data="modal()" x-if="true">
            <div class="fixed inset-0 z-50 overflow-y-auto" x-show="open && id === 'reject-modal'" x-transition>
                <div class="flex items-center justify-center min-h-screen px-4">
                    <!-- پس‌زمینه تیره -->
                    <div class="fixed inset-0 bg-black opacity-50" @click="close()"></div>

                    <!-- پنل مودال -->
                    <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-8" x-show="open" x-transition>
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-xl font-bold text-gray-800">
                                رد آگهی: {{ Str::limit($ad->title, 40) }}
                            </h3>
                            <button @click="close()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mb-5 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                            پس از رد، کاربر این دلیل را خواهد دید و آگهی منتشر نمی‌شود.
                        </div>

                        <form action="{{ route('admin.ads.reject', $ad) }}" method="POST">
                            @csrf
                            <label for="rejected_reason" class="block text-sm font-semibold text-gray-700 mb-2">
                                دلیل رد آگهی <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="rejected_reason"
                                id="rejected_reason"
                                rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200 transition"
                                placeholder="مثال: تصاویر نامناسب، اطلاعات ناقص، نقض قوانین سایت، قیمت غیرمنطقی..."
                                required
                            ></textarea>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button"
                                        @click="close()"
                                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
                                    انصراف
                                </button>
                                <button type="submit"
                                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow transition">
                                    رد کردن آگهی
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        {{-- اسکریپت Alpine.js برای مودال --}}
        <script>
            function modal() {
                return {
                    open: false,
                    id: null,
                    init() {
                        this.$watch('open', value => {
                            if (value) {
                                document.body.style.overflow = 'hidden';
                            } else {
                                document.body.style.overflow = 'auto';
                            }
                        });

                        // گوش دادن به رویداد باز کردن مودال
                        document.addEventListener('open-modal', (e) => {
                            this.id = e.detail.id;
                            this.open = true;
                        });
                    },
                    close() {
                        this.open = false;
                        this.id = null;
                    }
                }
            }
        </script>
        {{-- Main Image --}}
        <div class="mt-4">
            @if($ad->image)
                <img src="{{ $ad->image }}" class="w-full h-96 object-cover rounded-xl">
            @endif
        </div>

        {{-- Gallery --}}
        @if($ad->images->count() > 1)
            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($ad->images as $image)
                    @if(!$image->is_main)
                        <img
                            src="{{ url($image->image_path) }}"
                            class="h-32 w-full object-cover rounded-lg"
                        >
                    @endif
                @endforeach
            </div>
        @endif
        @if($ad->address)
            <div class="bg-gray-50 border rounded-xl p-5 mt-6">

                <h3 class="font-semibold text-lg mb-4">
                    اطلاعات آدرس
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">

                    <div>
                        <span class="font-medium">کشور:</span>
                        {{ $ad->address->country->name ?? '-' }}
                    </div>

                    <div>
                        <span class="font-medium">شهر:</span>
                        {{ $ad->address->city->name ?? '-' }}
                    </div>

                    @if($ad->address->region)
                        <div>
                            <span class="font-medium">منطقه:</span>
                            {{ $ad->address->region }}
                        </div>
                    @endif

                    @if($ad->address->full_address)
                        <div class="md:col-span-2">
                            <span class="font-medium">آدرس کامل:</span>
                            {{ $ad->address->full_address }}
                        </div>
                    @endif

                </div>

                @if($ad->address && $ad->address->latitude && $ad->address->longitude)
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg mb-3">
                            موقعیت روی نقشه
                        </h3>

                        <div
                            id="map"
                            class="w-full h-80 rounded-xl border"
                        ></div>
                    </div>
                @endif


            </div>
        @endif


        {{-- Info --}}
        <div class="bg-white mt-6 p-6 rounded-xl shadow">
            <h1 class="text-2xl font-bold mb-6">{{ $ad->title }}</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                <!-- ستون اول -->
                <div class="space-y-3">
                    <p><strong>دسته‌بندی:</strong> {{ $ad->category->title ?? '-' }}</p>
                    <p><strong>ثبت‌کننده:</strong> {{ $ad->user->name ?? '-' }}</p>
                    <p><strong>نوع آگهی:</strong> {{ ucfirst($ad->type) ?? '-' }}</p>
                    <p><strong>واحد ارز:</strong> {{ $ad->currency->title ?? '-' }}</p>
                    <p><strong>قیمت:</strong> {{ $ad->price ?? '-' }}</p>
                </div>

                <!-- ستون دوم -->
                <div class="space-y-3">
                    <p><strong>تاریخ انقضا:</strong>
                        {{ $ad->expiration_date ? \Carbon\Carbon::parse($ad->expiration_date)->format('Y-m-d') : '-' }}
                    </p>
                    <p><strong>تعداد بازدید:</strong> {{ $ad->view ?? 0 }}</p>

                    @if($ad->status)
                        <div>
                            <strong>وضعیت:</strong>
                            <span class="inline-block px-3 py-1 text-sm rounded-full ml-2
                        {{ $ad->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $ad->status == 'approved' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $ad->status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                    ">
                        {{ ucfirst($ad->status) }}
                    </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- توضیحات (تمام عرض زیر دو ستون) -->
            @if($ad->description)
                <div class="mt-6 text-gray-700 leading-relaxed border-t pt-6">
                    {{ $ad->description }}
                </div>
            @endif
        </div>

        <div class="bg-white mt-6 p-6 rounded-xl shadow space-y-3">
            @includeWhen($ad->digitalAd, 'admin.ads.digitalAd', ['ad' => $ad->digitalAd])
{{--            @includeWhen($ad->housingAds, 'admin.ads.housingAds', ['ad' => $ad->housingAds])--}}
            @includeWhen($ad->recruitmentAd, 'admin.ads.recruitmentAd', ['ad' => $ad->recruitmentAd])
            @includeWhen($ad->kitchenAds, 'admin.ads.kitchenAds', ['ad' => $ad->kitchenAds])
            @includeWhen($ad->vehiclesAds, 'admin.ads.vehiclesAds', ['ad' => $ad->vehiclesAds])
            @includeWhen($ad->serviceAds, 'admin.ads.serviceAds', ['ad' => $ad->serviceAds])
{{--            @includeWhen($ad->housemate, 'admin.ads.housemate', ['ad' => $ad->housemate])--}}

        </div>

    </div>
    @if($ad->address && $ad->address->latitude && $ad->address->longitude)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const lat = {{ $ad->address->latitude }};
                const lng = {{ $ad->address->longitude }};

                const map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup('موقعیت آگهی')
                    .openPopup();
            });
        </script>
    @endif

@endsection
