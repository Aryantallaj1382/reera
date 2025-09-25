<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceExpertise extends Model
{
    protected $guarded =[];
    public function service()
    {
        return $this->belongsTo(ServicesAd::class, 'service_expertise_id');
    }



}
