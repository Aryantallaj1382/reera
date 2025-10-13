<?php

namespace App\Models;

use App\Models\Category\Category;
use App\Models\Digital\DigitalAd;
use App\Models\Housemate\Housemate;
use App\Models\HousingAds\HousingAds;
use App\Models\Kitchen\KitchenAd;
use App\Models\Vehicle\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;


class Ad extends Model
{
//    use SoftDeletes;
    protected $table = 'ads';


    protected $guarded = [];

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
        if ($this->recruitmentAd) {
            return [
                'price' => $this->price,
                'time' => $this->recruitmentAd->time,
                'type' => $this->recruitmentAd->type,
                'currency' => $this?->recruitmentAd?->currency?->title,
                'icon' => null

            ];
        }
        if ($this->kitchenAds) {
            return [
                'condition' => $this->kitchenAds->condition,

            ];
        }
        if ($this->vehiclesAds) {
            return [
                'model' => $this->vehiclesAds?->model?->name,
                'brand' => $this->vehiclesAds?->brand?->name,

            ];
        }

        if ($this->serviceAds) {
            return [
                'expertise' => $this->serviceAds?->expertise?->name,


            ];
        }
        if ($this->housemate) {
            return [
                'x' => calculateCompatibilityPrecise($this->housemate->id, auth()->id()),

            ];
        }
        if ($this->personalAd) {
            return [
                'expertise' => $this->personalAd?->type?->name,

            ];
        }
        if ($this->businessAd) {
            return [
                'condition' => $this->businessAd?->condition,

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

        return $daysLeft;
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
        return $this->hasOne(BusinessAd::class);
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

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    /////// filtes
    public function scopeFilterCommon($query, $request)
    {
        $query->when($request->category_id ?? $request->category_slug, function ($q) use ($request) {
            $category = null;

            if ($request->category_id) {
                $category = Category::with('children')->find($request->category_id);
            } elseif ($request->category_slug) {
                $category = Category::with('children')->where('slug', $request->category_slug)->first();
            }

            if ($category) {
                $ids = $category->getAllIds()->toArray();
                $q->whereIn('category_id', $ids);
            }
        });


        $query->when($request->country_id, function ($q) use ($request) {
            $q->whereHas('address', function ($q2) use ($request) {
                $q2->where('country_id', $request->country_id);
            });
        });

        $query->when($request->city_id, function ($q) use ($request) {
            $q->whereHas('address', function ($q2) use ($request) {
                $q2->where('city_id', $request->city_id);
            });
        });

        $query->when($request->region, function ($q) use ($request) {
            $q->whereHas('address', function ($q2) use ($request) {
                $q2->where('region', $request->region);
            });
        });
        $query->when($request->currency, fn($q) => $q->where('currency_id', $request->currency));
        $query->when($request->min_price, fn($q) => $q->where('price', '>=', $request->min_price));
        $query->when($request->max_price, fn($q) => $q->where('price', '<=', $request->max_price));
        $query->when($request->has('is_verified'), fn($q) => $q->where('is_verified', $request->is_verified)
        );
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'expensive':
                $query->orderBy('price', 'desc');
                break;
            case 'cheap':
                $query->orderBy('price', 'asc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }
        return $query;
    }

    public function scopeFilterHousing($query, $request)
    {
        return $query->whereHas('housingAds', function ($q) use ($request) {
            if ($request->filled('bedrooms')) {
                $q->where('number_of_bedrooms', $request->bedrooms);
            }
            if ($request->filled('area_min')) {
                $q->where('area', '>=', $request->area_min);
            }
        });
    }

    public function scopeVehicles($query, $request)
    {
        return $query->whereHas('vehiclesAds', function ($q) use ($request) {
            if ($request->filled('bedrooms')) {
                $q->where('number_of_bedrooms', $request->bedrooms);
            }
            if ($request->filled('area_min')) {
                $q->where('area', '>=', $request->area_min);
            }
        });
    }

    public function scopeDigital($query, $request)
    {
        return $query->whereHas('digitalAd', function ($q) use ($request) {
            if ($request->filled('condition')) {
                $q->where('condition', $request->condition);
            }
        });
    }

    protected function rootCategorySlug(): Attribute
    {
        return Attribute::get(function () {
            $category = $this->category;

            while ($category && $category->parent) {
                $category = $category->parent;
            }

            return $category ? $category->slug : null;
        });
    }

    protected function rootCategoryTitle(): Attribute
    {
        return Attribute::get(function () {
            $category = $this->category;

            while ($category && $category->parent) {
                $category = $category->parent;
            }

            return $category ? $category->slug : null;
        });
    }

    public const statuses = [
        'approved' => 'تایید شده',
        'pending' => 'در حال بررسی',
        'rejected' => 'رد شده',
        'sold' => 'فروخته شده',
    ];




}
