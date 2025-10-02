<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body', 'user_id', 'parent_id', 'status',
        'owner_behavior_rating', 'price_clarity_rating',
        'info_honesty_rating', 'cleanliness_rating','commentable_type' , 'commentable_id'
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
    public function getAverageRatingAttribute()
    {
        $ratings = [
            $this->owner_behavior_rating,
            $this->price_clarity_rating,
            $this->info_honesty_rating,
            $this->cleanliness_rating,
        ];

        // حذف مقادیر null
        $filtered = array_filter($ratings, fn($value) => !is_null($value));

        if (empty($filtered)) {
            return null; // اگر هیچ مقداری ثبت نشده بود
        }

        return round(array_sum($filtered) / count($filtered), 1); // یک رقم اعشار
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function getIsLikedAttribute()
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->likes()->where('user_id', auth()->id())->exists();
    }


}
