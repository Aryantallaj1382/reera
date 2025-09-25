<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAttribute extends Model
{
    protected $fillable = [ 'value'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

