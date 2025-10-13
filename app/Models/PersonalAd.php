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

    public function getMobileAttribute()
    {
        if ($this->my_phone == true) {
            return $this->ad->user->mobile;
        } elseif ($this->other_phone == true) {
            return $this->other_phone_number;

        }
        return null;

    }

    // ارز
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
    }
    public function getConditionFaAttribute()
    {
        return match ($this->condition) {
            'new' => 'نو',
            'almost_new' => 'در حد نو',
            'used' => 'کارکرده',
            'needs_repair' => 'نیاز به تعمیر',
            default => 'نامشخص',
        };
    }

    public function getGenderUserAttribute()
    {
        return match ($this->gender){
            'man'=>'مرد',
            'woman'=>'زن'
        };
    }
}
