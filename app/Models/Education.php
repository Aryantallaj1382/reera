<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    protected $fillable = [
        'user_id',
        'major',
        'university_name',
        'degree',
        'start_year',
        'end_year',
        'is_current',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
