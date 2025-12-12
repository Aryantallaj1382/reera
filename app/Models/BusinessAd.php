<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessAd extends Model
{

    use HasFactory;

    protected $table = 'business_ads';

    protected $guarded = [];



    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

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
