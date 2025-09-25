<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $fillable = ['name', 'brand_id'];

    public function brand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    public function digitalAds()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_model_id');
    }
}
