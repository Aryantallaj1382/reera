@php
    use App\Models\Ad;
    use App\Models\Digital\DigitalAd;
@endphp
@extends('admin.layouts.app')

@section('content')
    <div class="product-card"
         style="max-width:900px; margin:20px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.1);">


        <div class="product-header" style="display:flex; gap:20px; flex-wrap:wrap;">
            <div class="product-images"
                 style="display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:15px; margin-bottom:20px;">
                @forelse($ticket->ad?->images ?? [] as $image)
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

{{--            <div class="product-image" style="flex:1; min-width:250px;">--}}
{{--                @foreach($ticket->ad?->images ?? [] as $image)--}}
{{--                    <div class="product-image" style="flex:1; min-width:250px;">--}}
{{--                        <img src="{{ asset($image->image_path) }}"--}}
{{--                             alt="عکس آگهی"--}}
{{--                             style="width:100%; height:300px; object-fit:cover; border-radius:12px;">--}}
{{--                @endforeach--}}
{{--                <img src="{{ asset($ticket->ad?->images->pluck('image_path')->toArray()) }}"--}}
{{--                     alt="عکس آگهی" style="width:100%; height:300px; object-fit:cover; border-radius:12px;">--}}
{{--            </div>--}}

{{--            @php--}}
                //                dd($vehicle);
{{--            @endphp--}}

            <div class="product-info" style="flex:2; min-width:250px;">
                <h2 style="margin-bottom:10px;">{{optional($ticket->ad?->first())?->title }}</h2>
                <p style="font-size:18px; color:#28a745; font-weight:bold;">قیمت:{{ number_format($ticket->price??0) }}{{$ticket->currency?->title}}</p>

                <div class="product-meta" style="margin-top:10px; line-height:1.6;">

                    <div><strong>نوع کالا:</strong> {{ $ticket->ticketType?->name ?? '-' }}</div>
                    <div><strong>آدرس:</strong> {{ optional($ticket->ad?->address()?->first())->full_address ?? '-' }}
                    </div>
                    <div>
                        <strong>کشور:</strong> {{ optional($ticket->ad?->address()?->first()?->country)->name ?? '-' }}
                    </div>
                    <div><strong>شهر:</strong> {{ optional($ticket->ad?->address()?->first()?->city)->name ?? '-' }}
                    </div>
                    <div><strong>تعداد:</strong> {{$ticket->number?? '-' }}</div>
                    <div><strong>تاریخ:</strong> {{$ticket->date?? '-' }}</div>
                    <div><strong>شماره:</strong> {{ $ticket->my_phone?'ندارد':'دارد'}}</div>
                    <div><strong>شماره تماس :</strong> {{$ticket->other_phone?'ندارد':'دارد' }}</div>
                    <div><strong>امکان چت:</strong> {{$ticket->site_massage? 'ندارد':'دارد'  }}</div>
                    <div><strong>پیش پراخت:</strong> {{$ticket->donation?? '-'  }}</div>
                    <div><strong>شماره تماس دیگر:</strong> {{$ticket->other_phone_number??'-' }}</div>

                    <div><strong>پرداخت نقدی:</strong> {{$ticket->cash? 'ندارد':'دارد' }}</div>
                    <div><strong>پرداخت اقساطی:</strong> {{$ticket->installments? 'ندارد':'دارد'}}</div>
                    <div><strong>پرادخت با چک:</strong> {{$ticket->check? 'ندارد':'دارد' }}</div>


                </div>
                <div class="product-description" style="margin-top:20px; line-height:1.6; color:#444;">
                    توضیحات: {{ $ticket->text ?? '-' }}
                </div>


                <form action="{{ route('personal.ads.updateStatus', $ticket->ad?->id) }}" method="POST"
                      style="margin-top:15px;">
                    @csrf
                    @method('PATCH')
                    <label for="status-{{ $ticket->ad?->id }}">وضعیت آگهی:</label>

                    <select name="status" id="status-{{ $ticket->ad?->id }}" class="status-select" size="4">
                        @foreach( Ad::statuses as $status=>$st)
                            <option
                                value="{{ $status }}"
                                @selected($ticket->ad?->status === $status)>
                                {{ ucfirst($st) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            style="padding:6px 12px; background:#007bff; color:white; border:none; border-radius:6px; cursor:pointer; margin-top:5px;">
                        بروزرسانی
                    </button>
                </form>
            </div>
        </div>


    </div>
@endsection
