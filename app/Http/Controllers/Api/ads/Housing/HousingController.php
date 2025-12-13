<?php

namespace App\Http\Controllers\Api\ads\Housing;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\HousingAds\HousingAds;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HousingController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::with(['housingAds', 'category', 'address']);

        $this->applyFilters($request, $query);
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'newest':
                    $query->latest();
                    break;
                case 'cheapest':
                    $query->join('housing_ads', 'ads.id', '=', 'housing_ads.ad_id')
                        ->orderBy('housing_ads.price', 'asc');
                    break;
                case 'most_expensive':
                    $query->join('housing_ads', 'ads.id', '=', 'housing_ads.ad_id')
                        ->orderBy('housing_ads.price', 'desc');
                    break;
            }
        } else {
            $query->latest();
        }

        $ads = $query->get();

        $transformed = $ads->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'slug' => $item->slug,
                'custom_info' => $item->custom_info,
                'image' => $item->main_image,
                'price' => optional($item->housingAds)->price,
                'time' => $item->time_ago,
                'location' => $item->location,
                'category' => optional($item->category)->title,
            ];
        });

        return api_response($transformed);
    }

    public function show($id)
    {
        $ad = Ad::with('housingAds')->find($id);
        $ad->increment('view');

        if(!$ad->housingAds)
        {
            return api_response([], 'wrong id');
        }
        $return =[
            'id' => $ad->id,
            'title' => $ad->title,
            'slug' => $ad->slug,
            'is_like' => $ad->is_like,
            'user_id' => $ad->user_id,

            'image' => getImages($ad->id),
            'address' => getAddress($ad->id),
            'seller' => getSeller($ad->id),
            'category' => $ad->category->title,
            'category_parent' => $ad->root_category_title,
            'price' => $ad->housingAds->price,
            'is_house' => true,
            'donation' => $ad->housingAds->donation,
            'currency_code' => $ad->housingAds?->currency?->code,
            'currency' => $ad->housingAds->currency?->title,
            'area' => $ad->housingAds->area,

            'number_of_bedrooms' => $ad->housingAds->number_of_bedrooms,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'year' => $ad->housingAds->year,
            'text' => $ad->housingAds->text,
            'family' => $ad->housingAds->family,
            'woman' => $ad->housingAds->woman,
            'distance' => [
                'distance_from_shopping_center' => $ad->housingAds->distance_from_shopping_center,
                'distance_from_taxi_stand' => $ad->housingAds->distance_from_taxi_stand,
                'distance_from_gas_station' => $ad->housingAds->distance_from_gas_station,
                'distance_from_hospital' => $ad->housingAds->distance_from_hospital,
                'distance_from_bus_station' => $ad->housingAds->distance_from_bus_station,
                'distance_from_airport' => $ad->housingAds->distance_from_airport,
            ] ,
            'man' => $ad->housingAds->man,
            'student' => $ad->housingAds->student,
            'rules' => $ad->housingAds->rules,
            'contact' => [
                'site_massage' => $ad->housingAds->site_massage,
                'my_phone' => $ad->housingAds->my_phone,
                'mobile' => $ad->housingAds->mobile,
            ],
            'use' => $ad->housingAds->use,
            'facilities'=> [
                'elevator' => $ad->housingAds->elevator,
                'parking' => $ad->housingAds->parking,
                'furnished' => $ad->housingAds->furnished,
                'internet' => $ad->housingAds->internet,
                'pet' => $ad->housingAds->pet,
                'washing_machine' => $ad->housingAds->washing_machine,
                'balcony' => $ad->housingAds->balcony,
                'system' => $ad->housingAds->system,
                'empty' => $ad->housingAds->empty,
                'in_use' => $ad->housingAds->in_use,
                'visit_from' => $ad->housingAds->visit_from,
                'storage' => $ad->housingAds->storage,
                'cooling' => $ad->housingAds->cooling,
                'heating' => $ad->housingAds->heating,
                'open_kitchen' => $ad->housingAds->open_kitchen,
                'cabinets' => $ad->housingAds->cabinets,
                'flooring' => $ad->housingAds->flooring,
                'security_door' => $ad->housingAds->security_door,
                'double_glazed_windows' => $ad->housingAds->double_glazed_windows,
                'security_guard' => $ad->housingAds->security_guard,
                'cctv' => $ad->housingAds->cctv,
                'generator' => $ad->housingAds->generator,
                'master_bedroom' => $ad->housingAds->master_bedroom,
                'guest_hall' => $ad->housingAds->guest_hall,
                'gym' => $ad->housingAds->gym,
                'pool' => $ad->housingAds->pool,
            ],


        ];
        return api_response($return);

    }

    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'housing')->with('children')->first();
        if (!$mainCategory) {
            return api_response([], 'دسته‌بندی اصلی پیدا نشد', false);
        }
        $mainChildren = $mainCategory->children->map(function ($child) {
            return [
                'id' => $child->id,
                'category' => $child->title,
                'title_en' => $child->title_en,

            ];
        });
        $extraChildren = [];
        if ($request->filled('category_id')) {
            $selectedCategory = Category::where('id', $request->category_id)->with('children')->first();

            if ($selectedCategory) {
                $extraChildren = $selectedCategory->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'category' => $child->title,
                    ];
                });
            }
        }

        $minPrice = HousingAds::min('price');
        $maxPrice = HousingAds::max('price');




        $minArea = HousingAds::min('area');
        $maxArea = HousingAds::max('area');


        $lang = Ad::whereRelation('category', 'slug', 'housing')->with('address')->get();
        $a = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();


        $minYear = HousingAds::min('year');
        $maxYear = HousingAds::max('year');
        return api_response([
            'main_category' => $mainChildren,
            'selected_category' => $extraChildren,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'min_area' => $minArea,
            'max_area' => $maxArea,
            'min_year' => $minYear,
            'max_year' => $maxYear,
            'loc' =>$a
        ]);
    }

    private function applyFilters(Request $request, Builder $query)
    {
        if ($request->filled('search')) {
            $query->where('title','like', '%'.$request->search.'%');
        }
        if ($request->filled('category_id')) {
            $this->filterCategory($query, $request->category_id);
        }

        if ($request->filled('category_id_2')) {
            $this->filterCategory($query, $request->category_id_2);
        }

        if ($request->filled('subcategory_id')) {
            $query->where('category_id', $request->subcategory_id);
        }

        if ($request->filled('city_id')) {
            $query->whereRelation('address','city_id', '=', $request->city_id);
        }

        if ($request->filled('country_id')) {
            $query->whereRelation('address','country_id', '=', $request->country_id);
        }

        if ($request->filled('region')) {
            $query->whereRelation('address','region', 'like', '%'.$request->region.'%');
        }



        if ($request->filled('number_of_bedrooms')) {
            $query->whereRelation('housingAds','number_of_bedrooms', '=', $request->number_of_bedrooms);
        }
        if ($request->filled('currencies_id')) {
            $query->whereRelation('housingAds','currencies_id', '=', $request->currencies_id);
        }


        if ($request->filled('min_price')) {
            $query->whereHas('housingAds', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('housingAds', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        if ($request->filled('min_area')) {
            $query->whereHas('housingAds', function ($q) use ($request) {
                $q->where('area', '>=', $request->min_area);
            });
        }

        if ($request->filled('max_area')) {
            $query->whereHas('housingAds', function ($q) use ($request) {
                $q->where('area', '<=', $request->max_area);
            });
        }

        $query->whereHas('housingAds', function ($q) use ($request) {
            if ($request->has('elevator') && $request->elevator) {
                $q->where('elevator', true);
            }
            if ($request->has('parking') && $request->parking) {
                $q->where('parking', true);
            }
            if ($request->has('furnished') && $request->furnished) {
                $q->where('furnished', true);
            } if ($request->has('has_balcony') && $request->has_balcony) {
                $q->where('has_balcony', true);
            }
             if ($request->has('has_balcony') && $request->has_balcony) {
                $q->where('has_balcony', true);
            }
             if ($request->has('has_balcony') && $request->has_balcony) {
                $q->where('has_balcony', true);
            }
             if ($request->has('has_balcony') && $request->has_balcony) {
                $q->where('has_balcony', true);
            }
             if ($request->has('has_balcony') && $request->has_balcony) {
                $q->where('has_balcony', true);
            }
             if ($request->has('has_balcony') && $request->has_balcony) {
                $q->where('has_balcony', true);
            }

        });
    }
    private function filterCategory(Builder $query, $categoryId)
    {
        $selectedCategory = Category::with('children', 'parent')->find($categoryId);

        if ($selectedCategory) {
            if ($selectedCategory->parent && $selectedCategory->children->count()) {
                $childIds = $selectedCategory->children->pluck('id')->toArray();
                $query->whereIn('category_id', array_merge([$selectedCategory->id], $childIds));
            } else {
                $query->where('category_id', $selectedCategory->id);
            }
        }
    }

}
