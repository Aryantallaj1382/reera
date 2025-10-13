@php use App\Models\Ad; @endphp
@extends('admin.layouts.app')

@section('content')
    <div class="product-card" style="max-width:900px; margin:20px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.1);">

        <div class="product-header" style="display:flex; gap:20px; flex-wrap:wrap;">
            <div class="product-images"
                 style="display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:15px; margin-bottom:20px;">
                @forelse($digital->ad?->images ?? [] as $image)
                    <div class="product-image"
                         style="overflow:hidden; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.1);">
                        <img src="{{ asset($image->image_path) }}"
                             alt="عکس آگهی"
                             style="width:100%; height:250px; object-fit:cover; transition:0.3s;">
                    </div>
                @empty
                    <p style="color:#777;">عکسی برای این آگهی ثبت نشده است.</p>
                @endforelse
            </div>

            <div class="product-info" style="flex:2; min-width:250px;">
                <h2 style="margin-bottom:10px;">{{ $digital->title ?? 'آگهی دیجیتال' }}</h2>
                <p style="font-size:18px; color:#28a745; font-weight:bold;">قیمت: {{ number_format($digital->price) }}{{$digital->currency?->title}}</p>

                <div class="product-meta" style="margin-top:10px; line-height:1.6;">
                    <div><strong>دسته‌بندی:</strong> {{ $digital->brand->name ?? '-' }}</div>
                    <div><strong>مدل:</strong> {{ $digital->model->name ?? '-' }}</div>
                    <div><strong>وضعیت محصول:</strong> {{ $digital->condition_fa ?? '-' }}</div>
                    <div><strong>قاب گوشی:</strong> {{ $digital->phone_case ?'ندارد' :'دارد' }}</div>
                    <div><strong>گلس گوشی:</strong> {{ $digital->glass ?'ندارد' :'دارد' }}</div>
                    <div><strong>پایه گوشی:</strong> {{ $digital->stand ?'ندارد' :'دارد' }}</div>
                    <div><strong>کابل گوشی:</strong> {{ $digital->cable ?'ندارد' :'دارد' }}</div>
                    <div><strong>آدرس:</strong> {{ optional($digital->ad->address()?->first())->full_address ?? '-' }}</div>
                    <div><strong>کشور:</strong> {{ optional($digital->ad->address()?->first()?->country)->name ?? '-' }}</div>
                    <div><strong>شهر:</strong> {{ optional($digital->ad->address()?->first()?->city)->name ?? '-' }}</div>
                    <div><strong>شماره تماس:</strong> {{ $digital->my_phone?? '-' }}</div>
                    <div><strong>شماره تماس دیگر:</strong> {{$digital->other_phone_number?? '-' }}</div>
                    <div><strong>پرداخت نقدی:</strong> {{$digital->cash? 'ندارد':'دارد' }}</div>
                    <div><strong>پرداخت اقساطی:</strong> {{$digital->installments? 'ندارد':'دارد'}}</div>
                    <div><strong>پرادخت با چک:</strong> {{$digital->check? 'ندارد':'دارد' }}</div>
                    <div><strong>امکان چت:</strong> {{$digital->site_massage? 'ندارد':'دارد'  }}</div>

                </div>
                <div class="product-description" style="margin-top:20px; line-height:1.6; color:#444;">
                    <strong>توضیحات:</strong>{{ $digital->text ?? '-' }}
                </div>


                <form action="{{ route('digital.ads.updateStatus', $digital->ad->id) }}" method="POST" style="margin-top:15px;">
                    @csrf
                    @method('PATCH')
                    <label for="status-{{ $digital->ad->id }}">وضعیت آگهی:</label>

                    <select
                        name="status"
                        id="status-{{ $digital->ad->id }}"
                        class="status-select"
                        size="4">
                        @foreach(Ad::statuses as $status=>$st)
                            <option
                                value="{{ $status }}"
                                @selected($digital->ad->status === $status)>
                                {{ ucfirst($st) }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" style="padding:6px 12px; background:#007bff; color:white; border:none; border-radius:6px; cursor:pointer; margin-top:5px;">بروزرسانی</button>
                </form>
            </div>
        </div>




    </div>
@endsection
