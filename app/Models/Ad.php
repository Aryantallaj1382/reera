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
                'key' => $this->housingAds->number_of_bedrooms,
                'value' => $this->housingAds->area,
                'price' => $this->housingAds->price,
                'currency' => $this->housingAds?->currency?->title,
                'code' => $this->housingAds->currency?->code,
                'type' => $this->root_category_slug,

            ];
        }

        if ($this->digitalAd) {
            return [
                'key' => $this->digitalAd->condition,
                'value' => $this->digitalAd->brand->name,
                'type' => $this->root_category_slug,
                'price' => $this->digitalAd->price,
                'currency' => $this->digitalAd->currency?->title,
                'code' => $this->digitalAd->currency?->code,
            ];
        }
        if ($this->recruitmentAd) {
            return [
                'key' => $this->category->name,
                'value' => $this->recruitmentAd->time,
                'type' => $this->root_category_slug,
                'price' => $this->recruitmentAd?->price,
                'currency' => $this->recruitmentAd->currency?->title,
                'code' => $this->recruitmentAd->currency?->code,
//                'type' => $this->recruitmentAd->type,
//                'currency' => $this?->recruitmentAd?->currency?->title,
//                'icon' => null

            ];
        }
        if ($this->kitchenAds) {
            return [
                'key' => $this->kitchenAds->type->name,
                'value' => $this->kitchenAds->brand->name,
                'type' => $this->root_category_slug,
                'price' => $this->kitchenAds->price,
                'currency' => $this->kitchenAds->currency?->title,
                'code' => $this->kitchenAds->currency?->code,

            ];
        }
        if ($this->vehiclesAds) {
            return [
                'key' => $this->vehiclesAds?->model?->name,
                'value' => $this->vehiclesAds?->brand?->name,
                'type' => $this->root_category_slug,
                'price' => $this->vehiclesAds->price,
                'currency' => $this->vehiclesAds->currency?->title,
                'code' => $this->vehiclesAds->currency?->code,

            ];
        }

        if ($this->serviceAds) {
            return [
                'key' => $this->serviceAds?->expertise?->name,
                'type' => $this->root_category_slug,

                'price' => $this->serviceAds->price,
                'currency' => $this->serviceAds?->currency?->title,
                'code' => $this->serviceAds->currency?->code,
            ];
        }
        if ($this->housemate) {
            return [
                'compatibility' => calculateCompatibilityPrecise($this->housemate->id, auth()->id()),
                'key' => $this->housemate->number_of_bedrooms,
                'value' => $this->housemate->area,
                'type' => $this->root_category_slug,
                'price' => $this->housemate->price,
                'currency' => $this->housemate->currency->title,
                'code' => $this->housemate->currency->code,
            ];
        }
        if ($this->ticket) {
            return [
                'key' => $this->ticket?->date ? Carbon::parse($this->ticket->date)->format('Y-m-d') : null,
                'value' => $this->ticket->ticketType?->name,
                'type' => $this->root_category_slug,
                'price' => $this->ticket->price,
                'currency' => $this->ticket->currency->title,
                'code' => $this->ticket->currency->code,
            ];
        }
        if ($this->personalAd) {
            return [
                'key' => $this->personalAd?->type?->name,
                'type' => $this->root_category_slug,
                'price' => $this->personalAd->price,
                'condition' => $this->personalAd->condition,
                'currency' => $this->personalAd?->currency?->title,
                'code' => $this->personalAd?->currency?->code,
            ];
        }
        if ($this->businessAd) {
            return [
                'condition' => $this->businessAd?->condition,
                'type' => $this->root_category_slug,
                'price' => $this->businessAd->price,
                'currency' => $this->businessAd?->currency?->title,
                'code' => $this->businessAd?->currency?->code,
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
    public function getIsLikeAttribute()
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
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
    public function visa()
    {
        return $this->hasOne(Visa::class);
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
    public function trip()
    {
        return $this->hasOne(Trip::class);
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
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
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
        $query->when($request->category_id, function ($q) use ($request) {
            $category = Category::with('children')->find($request->category_id);
            if ($category) {
                $ids = $category->getAllIds()->toArray();
                $q->whereIn('category_id', $ids);
            }
        });


        $query->when($request->category_slug, function ($q) use ($request) {
            $category = Category::with('children')->where('slug', $request->category_slug)->first();
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

            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;

            case 'view':
                $query->orderByDesc('view');
                break;

            case 'popular': // پر بازدید + جدیدتر
                $query->orderByDesc('view')
                    ->orderByDesc('created_at');
                break;

            case 'expensive':
                $query->orderBy('price', 'desc');
                break;

            case 'cheap':
                $query->orderBy('price', 'asc');
                break;

            default: // حالت پیش‌فرض
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
            if ($request->filled('bathroom')) {
                $q->where('number_of_bathroom', $request->bathroom);
            }
            if ($request->filled('min_area')) {
                $q->where('area', '>=', $request->min_area);
            }
            if ($request->filled('max_area')) {
                $q->where('area', '<=', $request->max_area);
            }
            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }
            if ($request->filled('min_year')) {
                $q->where('year', '>=', $request->min_year);
            }
            if ($request->filled('max_year')) {
                $q->where('year', '<=', $request->max_year);
            }
        });
    }

    public function scopeVehicles($query, $request)
    {
        return $query->whereHas('vehiclesAds', function ($q) use ($request) {
            if ($request->filled('min_year')) {
                $q->where('date_model', '>=', $request->min_year);
            }
            if ($request->filled('max_year')) {
                $q->where('date_model', '<=', $request->max_year);
            }
            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }
            if ($request->filled('brand_id')) {
                $q->where('vehicle_brand_id',  $request->brand_id);
            }
            if ($request->filled('model_id')) {
                $q->where('vehicle_model_id',  $request->model_id);
            }
            if ($request->filled('min_function')) {
                $q->where('function', '>=', $request->min_function);
            }
            if ($request->filled('max_function')) {
                $q->where('function', '<=', $request->max_function);
            }

        });
    }

    public function scopeDigital($query, $request)
    {
        return $query->whereHas('digitalAd', function ($q) use ($request) {
            if ($request->filled('condition')) {
                $q->where('condition', $request->condition);
            }
            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }
            if ($request->filled('brand_id')) {
                $q->where('digital_model_id',  $request->brand_id);
            }
            if ($request->filled('model_id')) {
                $q->where('digital_brand_id',  $request->model_id);
            }
        });
    }
    public function scopeKitchen($query, $request)
    {
        return $query->whereHas('kitchenAds', function ($q) use ($request) {
            if ($request->filled('condition')) {
                $q->where('condition', $request->condition);
            }
            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }
            if ($request->filled('brand_id')) {
                $q->where('kitchen_brand_id',  $request->brand_id);
            }
            if ($request->filled('model_id')) {
                $q->where('kitchen_type_id',  $request->model_id);
            }
        });
    }
    public function scopeVisa($query, $request)
    {
        return $query->whereHas('visa', function ($q) use ($request) {
            if ($request->filled('country_id')) {
                $q->where('country_id', $request->country_id);
            }
            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }
            if ($request->filled('type_id')) {
                $q->whereHas('types', function ($q) use ($request) {
                    $q->where('types.id', $request->type_id);

                });
            }
        });
    }
    public function scopePersonal($query, $request)
    {
        return $query->whereHas('personalAd', function ($q) use ($request) {
            if ($request->filled('condition')) {
                $q->where('condition', $request->condition);
            }
            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }

        });
    }
    public function scopeBusiness($query, $request)
    {
        return $query->whereHas('businessAd', function ($q) use ($request) {
            if ($request->filled('condition')) {
                $q->where('condition', $request->condition);
            }
            if ($request->filled('personal_ads_type_id')) {
                $q->where('personal_ads_type_id', $request->personal_ads_type_id);
            }
            if ($request->filled('gender')) {
                $q->where('gender', $request->gender);
            }


            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }
        });
    }
    public function scopeServices($query, $request)
    {
        return $query->whereHas('kitchenAds', function ($q) use ($request) {
            if ($request->filled('service_expertise_id')) {
                $q->where('service_expertise_id', $request->service_expertise_id);
            }

            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }
        });
    }
    public function scopeRecruitment($query, $request)
    {
        return $query->whereHas('recruitmentAd', function ($q) use ($request) {
            if ($request->filled('languages_id')) {
                $q->where('languages_id', $request->languages_id);
            }
            if ($request->filled('recruitment_categories_id')) {
                $q->where('recruitment_categories_id', $request->recruitment_categories_id);
            }
            if ($request->filled('cooperation')) {
                $q->where('type', $request->cooperation);
            }
            if ($request->filled('degree')) {
                $q->where('degree', $request->degree);
            }

            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }
        });
    }
    public function scopeTicket($query, $request)
    {
        return $query->whereHas('ticket', function ($q) use ($request) {
            if ($request->filled('ticket_type_id')) {
                $q->where('ticket_type_id', $request->ticket_type_id);
            }

            if ($request->filled('min_price')) {
                $q->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $q->where('price', '<=', $request->max_price);
            }
        });
    }
    public function scopeHousemate($query, $request)
    {
//        return $query->whereHas('kitchenAds', function ($q) use ($request) {
//            if ($request->filled('condition')) {
//                $q->where('condition', $request->condition);
//            }
//        });
    }
    public function scopeTrip($query, $request)
    {
//        return $query->whereHas('kitchenAds', function ($q) use ($request) {
//            if ($request->filled('condition')) {
//                $q->where('condition', $request->condition);
//            }
//        });
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

    protected static function booted()
    {
        static::creating(function ($ad) {
            if (empty($ad->expiration_date)) {
                $ad->expiration_date = Carbon::now()->addMonths(2);
            }
        });
    }


}
