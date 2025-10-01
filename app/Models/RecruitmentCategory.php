<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentCategory extends Model
{
    protected $fillable = ['name'];

    // یک دسته می‌تواند چند آگهی داشته باشد
    public function recruitmentAds()
    {
        return $this->hasMany(RecruitmentAd::class, 'recruitment_categories_id');
    }
}
