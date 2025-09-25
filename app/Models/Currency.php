<?php

namespace App\Models;

use App\Models\Digital\DigitalAd;
use App\Models\HousingAds\HousingAds;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['title', 'code'];

    public function digitalAds()
    {
        return $this->hasMany(DigitalAd::class, 'currencies_id');
    }
    public function housingAds()
    {
        return $this->hasMany(HousingAds::class, 'currencies_id');
    }
}

