<?php

namespace App\Models\Digital;

use Illuminate\Database\Eloquent\Model;

class DigitalModel extends Model
{
    protected $fillable = ['name', 'digital_brand_id'];

    public function brand()
    {
        return $this->belongsTo(DigitalBrand::class, 'digital_brand_id');
    }

    public function digitalAds()
    {
        return $this->hasMany(DigitalAd::class, 'digital_model_id');
    }
}
