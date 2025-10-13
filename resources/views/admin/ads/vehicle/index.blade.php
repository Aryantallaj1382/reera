@extends('admin.layouts.app')

@section('content')
    <div class="ads-container">
        <h2 class="page-title">لیست آگهی‌ها</h2>


        <div class="ads-grid">
            @foreach($vehicles as $vehicle)
                {{--                {{dd($mobile)}}--}}
                <div class="ad-card">
                    <div class="product-image"
                         style="display:flex; align-items:center; justify-content:center;
            width:300px; height:300px; border-radius:12px; overflow:hidden;">
                        <img src="{{ asset(optional($vehicle->ad?->images->where('is_main', 1)->first())->image_path) }}"
                             alt="عکس اصلی آگهی"
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div class="ad-body">
                        <div class="ad-meta">
                            <span>{{$vehicle->ad?->first()->title}}</span>
                        </div>
                        <div>
                            @if($vehicle->price)
                            <span>{{$vehicle->price}}{{$vehicle->currency?->title}}</span><hr>
                            @endif
                            <span>{{$vehicle->function}}</span>

                        </div>
                        <div class="ad-actions">

                            <a href="{{ route('vehicle.show', $vehicle->id) }}" class="btn btn-view">
                                مشاهده
                            </a>



                            <form action="{{ route('vehicle.destroy', $vehicle->id) }}" method="POST"
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
