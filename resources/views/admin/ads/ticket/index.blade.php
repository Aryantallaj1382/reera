@extends('admin.layouts.app')

@section('content')
    <div class="ads-container">
        <h2 class="page-title">لیست آگهی‌ها</h2>


        <div class="ads-grid">
            @foreach($tickets as $ticket)
                {{--                {{dd($mobile)}}--}}
                <div class="ad-card">
                    <div class="product-image"
                         style="display:flex; align-items:center; justify-content:center;
            width:300px; height:300px; border-radius:12px; overflow:hidden;">
                        <img src="{{ asset(optional($ticket->ad?->images->where('is_main', 1)->first())->image_path) }}"
                             alt="عکس اصلی آگهی"
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
{{--                    <div class="product-image" style="flex:1;width:300px;text-align: center; position: relative;height: 300px;">--}}
{{--                        <img src="{{ asset(optional($ticket->ad?->images->where('is_main', 1)->first())->image_path) }}"--}}
{{--                             alt="عکس اصلی آگهی"--}}
{{--                             style="width:100%; height:100%; object-fit:cover; border-radius:12px;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">--}}

{{--                        <img src="{{ asset(optional($ticket->ad->images->first())->image_path) }}"--}}
{{--                             alt="عکس آگهی" style="width:100%; height:300px; object-fit:cover; border-radius:12px;">--}}
{{--                    </div>--}}
                    <div class="ad-body">
                        <div class="ad-meta">
                            <span><strong>{{optional($ticket->ad?->first())->title}}</strong></span>
                        </div>
                        <div>
                            <span>{{$ticket->ticketType?->name}}</span><hr>
                        </div>
                        <div>
{{--//min-width:250px;//--}}

                        @if($ticket->price)
                                <span>{{$ticket->price}}{{$ticket->currency?->title}}</span>

                            @endif

                        </div>
                        <div class="ad-actions">

                            <a href="{{ route('ticket.show', $ticket->id) }}" class="btn btn-view">مشاهده</a>

                            <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST"
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

