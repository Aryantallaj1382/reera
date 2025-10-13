<?php

namespace App\Models\HousingAds;

use App\Models\Ad;
use App\Models\Currency;
use App\Models\Vehicle\Vehicle;
use Illuminate\Database\Eloquent\Model;

class HousingAds extends Model
{
    protected $casts = [
        'rules' => 'array',
    ];
    protected $guarded = [];

    protected $table = 'housing_ads';
    public function getUseAttribute()
    {
        if ($this->empty == true)
        {
            return [
                'type' => 'empty',
                'visit_from' => null

            ];
        }
        elseif ($this->in_use == true)
        {
            return [
                'type' => 'in_use',
                'visit_from' =>$this->visit_from,
            ];
        }
        elseif ($this->in_use == false)
        {
            return [
                'type' => 'empty',
                'visit_from' => $this->visit_from
            ];
        }


    }
    public function ad()
    {
        return $this->belongsTo(Ad::class);


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
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
    }
}
