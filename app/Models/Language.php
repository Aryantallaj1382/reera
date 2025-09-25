<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['title'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
