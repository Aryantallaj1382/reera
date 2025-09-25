<?php

namespace App\Models\Kitchen;

use Illuminate\Database\Eloquent\Model;

class KitchenBrand extends Model
{
    protected $guarded = [];
    public function kitchen()
    {
        return $this->hasMany(KitchenType::class, 'kitchen_brand_id');
    }

}
