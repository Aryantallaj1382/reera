<?php

namespace App\Models\Digital;

use Illuminate\Database\Eloquent\Model;

class DigitalBrand extends Model
{
    protected $fillable = ['name'];

    public function models()
    {
        return $this->hasMany(DigitalModel::class, 'digital_brand_id');
    }

    public function digitalAds()
    {
        return $this->hasMany(DigitalAd::class, 'digital_brand_id');
    }
}
