<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    protected $guarded = [];
    public function types()
    {
        return $this->belongsToMany(VisaType::class, 'visa_type_visa', 'visa_id', 'visa_type_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class);

    }
    public function getMobileAttribute()
    {
        if ($this->my_phone == true) {
            return $this?->ad?->user?->mobile;
        } elseif ($this->other_phone == true) {
            return $this->other_phone_number;

        }
        return null;

    }

}
