<?php

namespace App\Models\Housemate;

use App\Models\PersonalTrait;
use App\Models\UserAttribute;
use Illuminate\Database\Eloquent\Model;

class Housemate extends Model
{
    protected $casts = [
        'rules' => 'array',
    ];
    protected $guarded = [];
    public function personalTraits()
    {
        return $this->hasMany(PersonalTrait::class);
    }

    function calculateCompatibilityPrecise($ad, $userId)
    {
        $rules = $ad->rules; // آرایه استرینگ
        $userValues = UserAttribute::where('user_id', $userId)
            ->where('key', 'skills') // فرض کلید "skills"
            ->pluck('value')
            ->toArray();

        if (empty($rules) || empty($userValues)) return 0;

        $matches = array_intersect($rules, $userValues);

        $matchAdPercent = count($matches) / count($rules);

        $matchUserPercent = count($matches) / count($userValues);

        $compatibility = round((($matchAdPercent + $matchUserPercent) / 2) * 100, 2);

        return $compatibility;
    }

}
