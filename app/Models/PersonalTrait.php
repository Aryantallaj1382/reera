<?php

namespace App\Models;

use App\Models\Housemate\Housemate;
use Illuminate\Database\Eloquent\Model;

class PersonalTrait extends Model
{
    protected $guarded = [];
    public function housemate()
    {
        return $this->belongsTo(Housemate::class);
    }

}
