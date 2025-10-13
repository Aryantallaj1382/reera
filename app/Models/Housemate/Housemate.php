<?php

namespace App\Models\Housemate;

use App\Models\Ad;
use App\Models\Currency;
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
        $ad = Ad::find($ad);
        if (!$ad) return 0;
        $rules = $ad->rules;
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
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
    }
    public function getMobileAttribute()
    {
        if ($this->my_phone == true) {
            return $this->ad->user->mobile;
        } elseif ($this->other_phone == true) {
            return $this->other_phone_number;

        }
        return null;
    }

    public function getUseAttribute()
    {
        if ($this->empty == true)
        {
            return [
                'type' => 'empty',
                'visit_from' => null

            ];
        }
        elseif ($this->in_use == true)
        {
            return [
                'type' => 'in_use',
                'visit_from' =>$this->visit_from,
            ];
        }
        elseif ($this->in_use == false)
        {
            return [
                'type' => 'empty',
                'visit_from' => $this->visit_from
            ];
        }


    }

    }
