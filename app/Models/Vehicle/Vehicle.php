<?php

namespace App\Models\Vehicle;

use App\Models\Ad;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $guarded =[];

    // ارتباط با آگهی اصلی
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    // برند دیجیتال
    public function brand()
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
    }

    // مدل دیجیتال
    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    // ارز
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
}
