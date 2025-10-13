@extends('admin.layouts.app')

@section('content')
    <div class="ads-container">
        <h2 class="page-title">لیست آگهی‌ها</h2>


        <div class="ads-grid">
            @foreach($mobiles as $mobile)
                {{--                {{dd($mobile)}}--}}
                <div class="ad-card">
                    <div class="product-image"
                         style="display:flex; align-items:center; justify-content:center;
            width:300px; height:300px; border-radius:12px; overflow:hidden;">
                        <img src="{{ asset(optional($mobile->ad?->images->where('is_main', 1)->first())->image_path) }}"
                             alt="عکس اصلی آگهی"
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div class="ad-body">
                        <div class="ad-meta">
                            <span><strong>{{$mobile->ad->title}}</strong></span>
                        </div>
                        <div>
                            <span><strong>{{$mobile->condition_fa}}</strong></span><hr>

                        @if($mobile->price)
                            <span><strong>{{$mobile->price}}{{$mobile->currency?->title}}</strong></span>
                            @endif
                        </div>
                        <div class="ad-actions">

                            <a href="{{ route('digital.show', $mobile->id) }}" class="btn btn-view">
                                مشاهده
                            </a>

                            <form action="{{ route('digital.destroy', $mobile->id) }}" method="POST"
                                  onsubmit="return confirm('آیا مطمئن هستید؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">حذف</button>
                            </form>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
