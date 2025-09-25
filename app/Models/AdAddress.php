<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdAddress extends Model
{
    protected $fillable = [
        'ad_id', 'country_id', 'city_id', 'region', 'full_address', 'latitude', 'longitude',
    ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
