<?php

namespace App\Models\Kitchen;

use App\Models\Ad;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;

class KitchenAd extends Model
{
    protected $guarded =[];

    // ارتباط با آگهی اصلی
    public function ad()
    {
        return $this->belongsTo(Ad::class);
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
    public function brand()
    {
        return $this->belongsTo(KitchenBrand::class, 'kitchen_brand_id');
    }

    // مدل دیجیتال
    public function type()
    {
        return $this->belongsTo(KitchenType::class, 'kitchen_type_id');
    }

    // ارز
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
    }
    public function getConditionFaAttribute()
    {
        return match ($this->condition) {
            'new' => 'نو',
            'almost_new' => 'در حد نو',
            'used' => 'کارکرده',
            'needs_repair' => 'نیاز به تعمیر',
            default => 'نامشخص',
        };
    }
}
