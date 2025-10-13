@extends('admin.layouts.app')

@section('content')
    <div class="ads-container">
        <h2 class="page-title">لیست آگهی‌ها</h2>


        <div class="ads-grid">
            @foreach($personal as $per)
                {{--                {{dd($mobile)}}--}}
                <div class="ad-card">
                    <div class="product-image"
                         style="display:flex; align-items:center; justify-content:center;
            width:300px; height:300px; border-radius:12px; overflow:hidden;">
                        <img src="{{ asset(optional($per->ad?->images->where('is_main', 1)->first())->image_path) }}"
                             alt="عکس اصلی آگهی"
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div class="ad-body">
                        <div class="ad-meta">
                            <span><strong>{{optional($per->ad?->first())->title}}</strong></span>
                        </div>
                        <div>
                            <span>{{$per->condition_fa ?? '-'}}</span><hr>

                            @if($per->price)
                                <span>{{$per->price}}{{$per->currency?->title}}</span>

                            @endif

                        </div>
                        <div class="ad-actions">

                            <a href="{{ route('personal.show', $per->id) }}" class="btn btn-view">مشاهده</a>

                            <form action="{{ route('personal.destroy', $per->id) }}" method="POST"
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
