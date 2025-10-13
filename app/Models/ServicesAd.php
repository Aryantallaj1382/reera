<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ServicesAd extends Model
{
    protected $guarded =[];

    // ارتباط با آگهی اصلی
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    // برند دیجیتال
    public function expertise()
    {
        return $this->belongsTo(ServiceExpertise::class, 'service_expertise_id');
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
}
