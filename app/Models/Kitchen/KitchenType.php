<?php

namespace App\Models\Kitchen;

use Illuminate\Database\Eloquent\Model;

class KitchenType extends Model
{
    protected $guarded = [];
    public function kitchen()
    {
        return $this->hasMany(KitchenType::class, 'kitchen_type_id');
    }

}
