<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Model;

class VehicleBrand extends Model
{
    protected $fillable = ['name'];

    public function models()
    {
        return $this->hasMany(VehicleModel::class, 'brand_id');
    }

    public function vehicle()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_brand_id');
    }
}
