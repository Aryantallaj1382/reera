<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{

protected  $guarded = [];
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
