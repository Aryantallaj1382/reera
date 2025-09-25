<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAdType extends Model
{
    protected $guarded = [];
    public function personalAd()
    {
        return $this->hasMany(PersonalAd::class, 'personal_ads_type_id');
    }

}
