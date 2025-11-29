<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $guarded = [];
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



    public function origin_city()
    {
        return $this->belongsTo(City::class , 'origin_city_id');
    }
    public function origin_country()
    {
        return $this->belongsTo(Country::class, 'origin_country_id');
    }

    public function destination_country()
    {
        return $this->belongsTo(Country::class , 'destination_country_id');
    }

    public function destination_city()
    {
        return $this->belongsTo(City::class, 'destination_city_id');
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
}
