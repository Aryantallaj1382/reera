<?php

namespace App\Models\Housemate;

use App\Models\PersonalTrait;
use Illuminate\Database\Eloquent\Model;

class Housemate extends Model
{
    protected $casts = [
        'rules' => 'array',
    ];
    protected $guarded = [];
    public function personalTraits()
    {
        return $this->hasMany(PersonalTrait::class);
    }

}
