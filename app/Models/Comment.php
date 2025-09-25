<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body', 'user_id', 'parent_id', 'status',
        'owner_behavior_rating', 'price_clarity_rating',
        'info_honesty_rating', 'cleanliness_rating',
    ];
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'approved' => 'تایید شده',
            'pending' => 'در انتظار',
            'rejected' => 'رد شده',
            default => 'نامشخص',
        };
    }

    public function getStatusClassAttribute()
    {
        return match ($this->status) {
            'approved' => 'text-success',
            'pending' => 'text-warning',
            'rejected' => 'text-error',
            default => 'text-gray',
        };
    }
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
