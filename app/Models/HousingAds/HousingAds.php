<?php

namespace App\Models\HousingAds;

use Illuminate\Database\Eloquent\Model;

class HousingAds extends Model
{
    protected $casts = [
        'rules' => 'array',
    ];
    protected $guarded = [];

    protected $table = 'housing_ads';

}
