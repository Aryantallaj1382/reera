<?php

namespace App\Http\Controllers\Api\ads\Digital;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\Digital\DigitalAd;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DigitalController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::with(['digitalAd', 'category', 'address'])->where('category_id',2);

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

    public function show($slug)
    {
        $ad = Ad::where('slug', $slug)->with('digitalAd')->first();
        $return =[
            'id' => $ad->id,
            'title' => $ad->title,
            'slug' => $ad->slug,
            'image' => $ad->images->pluck('image_path')->toArray(),
            'price' => $ad->digitalAd->price,
            'time' => $ad->time_ago,
            'location' => $ad->location,
            'membership' => $ad->user->membership_duration,
            'user' => $ad->user->name,
            'category' => $ad->category->title,
            'phone_case' => $ad->digitalAd->phone_case,
            'parking' => $ad->digitalAd->parking,
            'furnished' => $ad->digitalAd->furnished,
            'internet' => $ad->digitalAd->internet,
            'pet' => $ad->digitalAd->pet,
            'washing_machine' => $ad->digitalAd->washing_machine,
            'balcony' => $ad->digitalAd->balcony,
            'system' => $ad->digitalAd->system,
            'empty' => $ad->digitalAd->empty,
            'in_use' => $ad->digitalAd->in_use,
            'visit_from' => $ad->digitalAd->visit_from,
            'number_of_bedrooms' => $ad->digitalAd->number_of_bedrooms,
            'year' => $ad->digitalAd->year,
            'area' => $ad->digitalAd->area,
            'text' => $ad->digitalAd->text,
            'family' => $ad->digitalAd->family,
            'woman' => $ad->digitalAd->woman,
            'man' => $ad->digitalAd->man,
            'student' => $ad->digitalAd->student,
            'rules' => $ad->digitalAd->rules,


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

        $minPrice = DigitalAd::min('price');
        $maxPrice = DigitalAd::max('price');

        $lang = Ad::where('category_id', 2)->with('address')->get();
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


        if ($request->filled('currencies_id')) {
            $query->whereRelation('digitalAd','currencies_id', '=', $request->currencies_id);
        }


        if ($request->filled('min_price')) {
            $query->whereHas('digitalAd', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('digitalAd', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }


        $query->whereHas('digitalAd', function ($q) use ($request) {
            if ($request->has('phone_case') && $request->phone_case) {
                $q->where('phone_case', true);
            }
            if ($request->has('glass') && $request->glass) {
                $q->where('glass', true);
            }
            if ($request->has('stand') && $request->stand) {
                $q->where('stand', true);
            } if ($request->has('cable') && $request->cable) {
                $q->where('cable', true);
            }
            if ($request->has('condition') && $request->condition) {
                $q->where('condition', $request->condition);
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
