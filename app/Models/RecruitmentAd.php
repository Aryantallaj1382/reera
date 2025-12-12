<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentAd extends Model
{
    protected $guarded = [];
    protected $casts = [
        'details' => 'array',
        'skill'   => 'array',
        'role'    => 'array',

    ];

    // روابط

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
    public function category()
    {
        return $this->belongsTo(RecruitmentCategory::class, 'recruitment_categories_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
    }
    public function getMobileAttribute()
    {
        if ($this->my_phone == true) {
            return $this->ad->user->mobile;
        } elseif ($this->other_phone == true) {
            return $this->other_phone_number;

        }
        return null;

    }
    public function language()
    {
        return $this->belongsTo(Language::class, 'languages_id');
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
