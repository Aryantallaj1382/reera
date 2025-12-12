<?php

namespace App\Http\Controllers\Api\ads\Business;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\HousingAds\HousingAds;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'business')->with('children')->first();
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

        $lang = Ad::whereRelation('category', 'slug', 'business')->with('address')->get();
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

    public function show($id)
    {

        $ad = Ad::with('businessAd')->find($id);

        if(!$ad->businessAd)
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
            'price' => $ad->businessAd->price,
            'donation' => $ad->businessAd->donation,
            'check' => $ad->businessAd->check,
            'installments' => $ad->businessAd->installments,
            'cash' => $ad->businessAd->cash,
            'currency_code' => $ad->businessAd?->currency?->code,
            'currency' => $ad->businessAd->currency?->title,
            'condition' => $ad->businessAd->condition,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'text' => $ad->businessAd->text,
            'contact' => [
                'site_massage' => $ad->businessAd->site_massage,
                'my_phone' => $ad->businessAd->my_phone,
                'mobile' => $ad->businessAd->mobile,
            ],



        ];
        return api_response($return);

    }

}
