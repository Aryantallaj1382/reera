<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PersonalAd extends Model
{
    protected $guarded =[];

    // ارتباط با آگهی اصلی
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    // برند دیجیتال
    public function type()
    {
        return $this->belongsTo(PersonalAdType::class, 'personal_ads_type_id');
    }



    // ارز
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
    }
}
