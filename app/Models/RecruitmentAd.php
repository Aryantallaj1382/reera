<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentAd extends Model
{
    protected $fillable = [
        'ad_id',
        'languages_id',
        'recruitment_categories_id',
        'days',
        'time',
        'degree',
        'details',
        'skill',
        'role',
        'site_massage',
        'my_phone',
        'other_phone',
        'other_phone_number',
        'currencies_id',
        'price',
        'donation',
        'cash',
        'installments',
        'check',
        'type',
        'plan_type',
        'pyment_status',
    ];

    protected $casts = [
        'details' => 'array',
        'skill'   => 'array',
        'role'    => 'array',
        'site_massage' => 'boolean',
        'my_phone'     => 'boolean',
        'other_phone'  => 'boolean',
        'cash'         => 'boolean',
        'installments' => 'boolean',
        'check'        => 'boolean',
    ];

    // روابط

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'languages_id');
    }

    public function category()
    {
        return $this->belongsTo(RecruitmentCategory::class, 'recruitment_categories_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
    }

    public function getPlanTypeNameAttribute()
    {
        return match ($this->plan_type) {

            'free' => 'رایگان',
            'vip' => 'وی آی پی',
        };

    }
    public function getPymentStatusNameAttribute()
    {
        return match ($this->pyment_status) {

            'pending' => 'در حال بررسی',
            'paid' => 'پرداخت شده',
        };
    }
}
