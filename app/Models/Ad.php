<?php

namespace App\Models;

use App\Models\Category\Category;
use App\Models\Digital\DigitalAd;
use App\Models\Housemate\Housemate;
use App\Models\HousingAds\HousingAds;
use App\Models\Kitchen\KitchenAd;
use App\Models\Vehicle\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;

class Ad extends Model
{
//    use SoftDeletes;
    protected $table = 'ads';


    protected $fillable = [
        'user_id', 'category_id', 'title', 'status', 'type' , 'created_at' , 'price'
    ];
    public function getCustomInfoAttribute()
    {
        if ($this->housingAds) {
            return [
                'bedrooms' => $this->housingAds->number_of_bedrooms,
                'area' => $this->housingAds->area,
            ];
        }

        if ($this->digitalAd) {
            return [
                'condition' => $this->digitalAd->condition,
            ];
        }
        if ($this->kitchenAds) {
            return [

            ];
        }
        if ($this->vehiclesAds) {
            return [
            ];
        }
        if ($this->recruitmentAd) {
            return [
                'type'

            ];
        }
        if ($this->serviceAds) {
            return [

            ];
        }
        if ($this->housemate) {
            return [

            ];
        }
        if ($this->personalAd) {
            return [

            ];
        }

        return null; // بهتره null برگردونی نه 22 مگر اینکه 22 معنای خاصی داشته باشه
    }

    public function getRemainingAttribute()
    {
        if (!$this->created_at) {
            return null;
        }

        $expireDate = $this->created_at->copy()->addDays(30);
        $now = Carbon::now();

        if ($now->greaterThan($expireDate)) {
            return 'expired';
        }

        $daysLeft = round($now->floatDiffInDays($expireDate));

        if ($daysLeft == 1) {
            return 'today';
        }

        return $daysLeft ;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function images()
    {
        return $this->hasMany(AdImage::class);
    }

    public function mainImages()
    {
        return $this->hasOne(AdImage::class)->where('is_main', true);
    }

    public function getImageAttribute()
    {
        $image = $this->mainImages()->first();

        if (!$image) {
            return null;
        }
        return url($image->image_path);
    }
    public function address()
    {
        return $this->hasOne(AdAddress::class);
    }

    // مثال برای رابطه با جدول ویژگی خاص (مثلا خودرو)
     public function housingAds()
     {
         return $this->hasOne(HousingAds::class);
     }
    public function vehiclesAds()
    {
        return $this->hasOne(Vehicle::class);
    }
    public function recruitmentAd()
    {
        return $this->hasOne(RecruitmentAd::class);
    }
    public function ticket()
    {
        return $this->hasOne(TicketAd::class);
    }
    public function kitchenAds()
    {
        return $this->hasOne(KitchenAd::class);
    }
    public function serviceAds()
    {
        return $this->hasOne(ServicesAd::class);
    }
    public function housemate()
    {
        return $this->hasOne(Housemate::class);
    }
    public function digitalAd()
    {
        return $this->hasOne(DigitalAd::class);
    }
    public function personalAd()
    {
        return $this->hasOne(PersonalAd::class);
    }
    public function businessAd()
    {
        return $this->hasOne(PersonalAd::class);
    }


    public function getLocationAttribute(): string
    {
        if ($this->address && $this->address->city && $this->address->country) {
            return $this->address->city->name . '، ' . $this->address->country->name;
        }

        return 'نامشخص';
    }


    public function getTimeAgoAttribute()
    {
        $locale = app()->getLocale();
        $created = $this->created_at;

        switch ($locale) {
            case 'fa':
                return Jalalian::fromDateTime($created)->ago();
            case 'ar':
                // می‌تونی بعداً یک پکیج بهتر برای عربی نصب کنی
                return __('messages.ad_time_ago', ['time' => Carbon::parse($created)->diffForHumans(null, true, false, 2)]);
            case 'en':
            default:
                return __('messages.ad_time_ago', ['time' => Carbon::parse($created)->diffForHumans(null, true, false, 2)]);
        }
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->title);
            }
        });
    }

    protected static function generateUniqueSlug($base)
    {
        $slug = Str::slug($base);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
