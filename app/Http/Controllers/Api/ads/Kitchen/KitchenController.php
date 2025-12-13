<?php

namespace App\Http\Controllers\Api\ads\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\HousingAds\HousingAds;
use App\Models\Kitchen\KitchenAd;
use App\Models\Kitchen\KitchenBrand;
use App\Models\Kitchen\KitchenType;
use App\Models\RecruitmentAd;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function show($id)
    {

        $ad = Ad::with('kitchenAds')->find($id);
        $ad->increment('view');


        if(!$ad->kitchenAds)
        {
            return api_response([], 'wrong id');
        }
        $return =[

            'id' => $ad->id,
            'title' => $ad->title,
            'is_like' => $ad->is_like,
            'user_id' => $ad->user_id,

            'slug' => $ad->slug,
            'image' => getImages($ad->id),
            'address' => getAddress($ad->id),
            'seller' => getSeller($ad->id),
            'category' => $ad->category->title,
            'category_parent' => $ad->root_category_title,
            'price' => $ad->kitchenAds->price,
            'type' => $ad->kitchenAds->type?->name,
            'brand' => $ad->kitchenAds->brand?->name,
            'donation' => $ad->kitchenAds->donation,
            'check' => $ad->kitchenAds->check,
            'installments' => $ad->kitchenAds->installments,
            'cash' => $ad->kitchenAds->cash,
            'currency_code' => $ad->kitchenAds?->currency?->code,
            'currency' => $ad->kitchenAds->currency?->title,
            'condition' => $ad->kitchenAds->condition,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'text' => $ad->kitchenAds->text,
            'contact' => [
                'site_massage' => $ad->kitchenAds->site_massage,
                'my_phone' => $ad->kitchenAds->my_phone,
                'mobile' => $ad->kitchenAds->mobile,
            ],



        ];
        return api_response($return);

    }

    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'kitchen')->with('children')->first();
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

        $lang = Ad::whereRelation('category', 'slug', 'kitchen')->with('address')->get();
        $a = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();
        $brand = KitchenBrand::all();
        $model = KitchenType::all();
        $minPrice =KitchenAd::min('price');
        $maxPrice =KitchenAd::max('price');


        return api_response([
            'main_category' => $mainChildren,
            'selected_category' => $extraChildren,
            'brands' => $brand,
            'models' => $model,
            'loc' =>$a,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }

}
