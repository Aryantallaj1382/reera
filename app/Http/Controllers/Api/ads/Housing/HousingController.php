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

        // مرتب‌سازی
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

    public function show($slug)
    {
        $ad = Ad::where('slug', $slug)->with('housingAds')->first();
        $return =[
            'id' => $ad->id,
            'title' => $ad->title,
            'slug' => $ad->slug,
            'image' => $ad->images->pluck('image_path')->toArray(),
            'price' => $ad->housingAds->price,
            'time' => $ad->time_ago,
            'location' => $ad->location,
            'membership' => $ad->user->membership_duration,
            'user' => $ad->user->name,
            'category' => $ad->category->title,
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
            'number_of_bedrooms' => $ad->housingAds->number_of_bedrooms,
            'year' => $ad->housingAds->year,
            'area' => $ad->housingAds->area,
            'text' => $ad->housingAds->text,
            'family' => $ad->housingAds->family,
            'woman' => $ad->housingAds->woman,
            'man' => $ad->housingAds->man,
            'student' => $ad->housingAds->student,
            'rules' => $ad->housingAds->rules,


        ];
        return api_response($return);

    }

    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('id', 1)->with('children')->first();

        if (!$mainCategory) {
            return api_response([], 'دسته‌بندی اصلی پیدا نشد', false);
        }
        $mainChildren = $mainCategory->children->map(function ($child) {
            return [
                'id' => $child->id,
                'category' => $child->title,
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

        $lang = Ad::where('category_id', 1)->with('address')->get();
        $a = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();

        return api_response([
            'main_category' => $mainChildren,
            'selected_category' => $extraChildren,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'min_area' => $minArea,
            'max_area' => $maxArea,
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
