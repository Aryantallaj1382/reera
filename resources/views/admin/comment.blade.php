@extends('admin.layouts.app')

@section('content')


    <div class="main-content">
        <div class="tab__box">
            <div class="tab__items">
                <a class="tab__item {{ is_null($status) ? 'is-active' : '' }}" href="{{ route('comments.index') }}">همه نظرات</a>
                <a class="tab__item {{ $status == 'pending' ? 'is-active' : '' }}" href="{{ route('comments.index', ['status' => 'pending']) }}">نظرات تایید نشده</a>
                <a class="tab__item {{ $status == 'approved' ? 'is-active' : '' }}" href="{{ route('comments.index', ['status' => 'approved']) }}">نظرات تایید شده</a>
                <a class="tab__item {{ $status == 'rejected' ? 'is-active' : '' }}" href="{{ route('comments.index', ['status' => 'rejected']) }}">نظرات رد شده</a>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
        </div>

        <div class="bg-white padding-20">
            <div class="t-header-search">
                <form action="" onclick="event.preventDefault();">
                    <div class="t-header-searchbox font-size-13">
                        <input type="text" class="text search-input__box font-size-13" placeholder="جستجوی در نظرات">
                        <div class="t-header-search-content">
                            <input type="text" class="text" placeholder="قسمتی از متن">
                            <input type="text" class="text" placeholder="ایمیل">
                            <input type="text" class="text margin-bottom-20" placeholder="نام و نام خانوادگی">
                            <button class="btn btn-netcopy_net">جستجو</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>ارسال کننده</th>
                    <th>برای</th>
                    <th>دیدگاه</th>
                    <th>تاریخ</th>
                    <th>تعداد پاسخ‌ها</th>
                    <th>امتیاز</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($comments as $comment)
                    <tr>
                        <td>{{ $comment->id }}</td>
                        <td>{{ $comment->user->name ?? '---' }}</td>
                        <td>
                            @php
                                $target = $comment->commentable;
                            @endphp
                            {{ $target->title ?? class_basename($comment->commentable_type) . ' #' . $comment->commentable_id }}
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($comment->body, 50) }}</td>
                        <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($comment->created_at)->format('Y/m/d') }}</td>
                        <td>{{ $comment->replies->count() }}</td>
                        <td>
                            @php
                                $ratings = collect([
                                    $comment->owner_behavior_rating,
                                    $comment->price_clarity_rating,
                                    $comment->info_honesty_rating,
                                    $comment->cleanliness_rating
                                ])->filter();
                            @endphp
                            {{ $ratings->isNotEmpty() ? number_format($ratings->avg(), 1) . ' / 5' : 'بدون امتیاز' }}
                        </td>
                        <td>
                            @switch($comment->status)
                                @case('approved')
                                    <span class="text-success">تایید شده</span>
                                    @break
                                @case('pending')
                                    <span class="text-warning">در انتظار</span>
                                    @break
                                @case('rejected')
                                    <span class="text-error">رد شده</span>
                                    @break
                                @default
                                    <span class="text-gray-500">نامشخص</span>
                            @endswitch
                        </td>

                        <td>
                            <form action="{{ route('comments.approve', $comment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="item-confirm mlg-15" title="تایید" onclick="return confirm('آیا از تایید این نظر مطمئن هستید؟')"></button>
                            </form>

                            <form action="{{ route('comments.reject', $comment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="item-reject mlg-15" title="رد" onclick="return confirm('آیا از رد این نظر مطمئن هستید؟')"></button>
                            </form>

                            <!-- لینک‌های دیگر مثل حذف، مشاهده و ویرایش هم اینجا می‌تونن باشن -->
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="item-delete mlg-15" title="حذف" onclick="return confirm('آیا از حذف این نظر مطمئن هستید؟')"></button>
                            </form>
                            <a href="#" class="item-eye mlg-15" title="مشاهده"></a>
                            <a href="#" class="item-edit" title="ویرایش"></a>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $comments->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
