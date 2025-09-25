@extends('admin.layouts.app')

@section('content')
    <div class="ad-show-wrapper">
        <div class="ad-show-card">
            <div class="ad-show-image">
                <img src="{{ asset('aa.png') }}" alt="آگهی" class="ad-image">
            </div>
            <div class="ad-show-content">
                <h1 class="ad-show-title">اجاره آپارتمان مبله در سعادت‌آباد</h1>
                <div class="ad-show-tags">
                    <span class="tag">آپارتمان</span>
                    <span class="tag">اجاره‌ای</span>
                </div>
                <div class="ad-show-meta">
                    <p><i class="fa fa-map-marker-alt"></i> تهران، سعادت‌آباد</p>
                    <p><i class="fa fa-calendar-alt"></i> تاریخ ثبت: ۱۴۰۳/۰۵/۰۵</p>
                    <p><i class="fa fa-eye"></i> ۱۲۵ بازدید</p>
                </div>
                <p class="ad-show-description">
                    این آپارتمان با امکانات کامل، نورگیر عالی، پارکینگ اختصاصی، و مبلمان مدرن، برای اجاره کوتاه‌مدت مناسب است.
                </p>
                <div class="ad-show-price">
                    <span>۴٬۵۰۰٬۰۰۰ تومان / ماه</span>
                </div>
                <div class="ad-show-actions">
                    <button class="btn-approve">تایید</button>
                    <button class="btn-reject">رد کردن</button>
                </div>
            </div>
        </div>
    </div>
@endsection
