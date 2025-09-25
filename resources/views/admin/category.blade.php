@extends('admin.layouts.app')

@section('content')
    <div class="main-content padding-0 categories">
        <div class="row no-gutters">
            <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
                <p class="box__title">دسته بندی ها</p>
                <div class="table__box">
                    <table class="table">
                        <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>نام دسته بندی</th>
                            <th>اسلاگ</th>
                            <th>دسته پدر</th>
                            <th>فرزندان</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->title }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>ندارد</td>
                                <td>
                                    @if($category->children->count() > 0)
                                        <ul>
                                            @foreach($category->children as $child)
                                                <li>{{ $child->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        ندارد
                                    @endif
                                </td>

                            </tr>
                            {{-- اگر می‌خواهید فرزندان سطح بعدی را هم نمایش دهید، می‌توانید بازگشتی بنویسید یا چند لایه اضافه کنید --}}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>@endsection
