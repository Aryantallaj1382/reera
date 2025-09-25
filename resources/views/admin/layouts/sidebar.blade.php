        <div class="sidebar__nav border-top border-left">
            <span class="bars d-none padding-0-18"></span>
            <a class="header__logo d-none" href="https://netcopy.ir"></a>
            <div class="profile__info border cursor-pointer text-center">
                <div class="avatar__img">
                    <img src="{{ asset('admin/img/pro.jpg') }}" class="avatar___img">
                    <input type="file" accept="image/*" class="hidden avatar-img__input">
                    <div class="v-dialog__container" style="display: block;"></div>
                    <div class="box__camera default__avatar"></div>
                </div>
                <span class="profile__name">کاربر : ادمین</span>
            </div>

            <ul>
                <li class="item-li i-dashboard {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    <a href="{{ route('dashboard') }}">پیشخوان</a>
                </li>

                <li class="item-li i-comments {{ request()->routeIs('comments.*') ? 'is-active' : '' }}">
                    <a href="{{ route('comments.index') }}">نظرات</a>
                </li>

                <li class="item-li i-categories {{ request()->routeIs('category') ? 'is-active' : '' }}">
                    <a href="{{ route('category') }}">دسته بندی ها</a>
                </li>
{{--                <li class="item-li i-courses  {{ request()->routeIs('ads.index') ? 'is-active' : '' }}">--}}
{{--                    <a href="{{ route('ads.index') }}">آگهی ها</a>--}}
{{--                </li>--}}
                <ul class="sidebar-menu">
                    <li class="item-li i-courses">
                        <a href="javascript:void(0);" onclick="toggleSubmenu(this)">آگهی‌ها</a>
                        <ul class="submenu" style="display: none;">
                            <li><a class="no-before" href="{{ route('ads.index') }}">آگهی‌های ملکی</a></li>
                            <li><a class="no-before" href="#">آگهی‌های خودرو</a></li>
                            <li><a class="no-before" href="#">آگهی‌های شغلی</a></li>
                        </ul>
                    </li>
                </ul>
                <script>
                    function toggleSubmenu(element) {
                        const li = element.parentElement;
                        const submenu = li.querySelector('.submenu');
                        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                        li.classList.toggle('open');
                    }
                </script>


                <li class="item-li i-courses "><a href="courses.html">دوره ها</a></li>
                <li class="item-li i-users"><a href="users.html"> کاربران</a></li>
                <li class="item-li i-slideshow"><a href="slideshow.html">اسلایدشو</a></li>
                <li class="item-li i-banners"><a href="banners.html">بنر ها</a></li>
                <li class="item-li i-articles"><a href="articles.html">مقالات</a></li>
                <li class="item-li i-ads"><a href="ads.html">تبلیغات</a></li>
                <li class="item-li i-tickets"><a href="tickets.html"> تیکت ها</a></li>
                <li class="item-li i-discounts"><a href="discounts.html">تخفیف ها</a></li>
                <li class="item-li i-transactions"><a href="transactions.html">تراکنش ها</a></li>
                <li class="item-li i-checkouts"><a href="checkouts.html">تسویه حساب ها</a></li>
                <li class="item-li i-checkout__request "><a href="checkout-request.html">درخواست تسویه </a></li>
                <li class="item-li i-my__purchases"><a href="mypurchases.html">خرید های من</a></li>
                <li class="item-li i-my__peyments"><a href="mypeyments.html">پرداخت های من</a></li>
                <li class="item-li i-notification__management"><a href="notification-management.html">مدیریت اطلاع رسانی</a>
                </li>
                <li class="item-li i-user__inforamtion"><a href="user-information.html">اطلاعات کاربری</a></li>
            </ul>
        </div>
