<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'user_info';

    protected $fillable = [
        'user_id',
        'resume_file',
        'intro_video',
        'residency_status',
        'min_salary',
        'max_salary',
    ];

    /**
     * ارتباط با مدل User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getIntroVideoAttribute($value)
    {
        return $value ? url($value) : null;

    }
}
