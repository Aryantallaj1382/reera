<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaType extends Model
{
    protected $guarded = [];
    protected $table = 'visa_types';
    public function visas()
    {
        return $this->belongsToMany(Visa::class, 'visa_type_visa', 'visa_type_id', 'visa_id');
    }

}
