<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'company_name',
        'start_month',
        'start_year',
        'end_month',
        'end_year',
        'is_current',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
