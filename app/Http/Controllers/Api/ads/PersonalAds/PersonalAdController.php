<?php

namespace App\Http\Controllers\Api\ads\PersonalAds;

use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\Kitchen\KitchenAd;
use App\Models\Kitchen\KitchenBrand;
use App\Models\Kitchen\KitchenType;
use App\Models\PersonalAdType;
use Illuminate\Http\Request;

class PersonalAdController
{
    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'personal')->with('children')->first();
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

        $lang = Ad::whereRelation('category', 'slug', 'personal')->with('address')->get();
        $loc = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();
        $a = PersonalAdType::all();


        $minPrice =KitchenAd::min('price');
        $maxPrice =KitchenAd::max('price');
        return api_response([
            'main_category' => $mainChildren,
            'selected_category' => $extraChildren,
            'type' => $a,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,

            'loc' =>$loc
        ]);
    }

    public function show($id)
    {

        $ad = Ad::with('personalAd')->find($id);
        $ad->increment('view');

        if(!$ad->personalAd)
        {
            return api_response([], 'wrong id');
        }
        $return =[

            'id' => $ad->id,
            'is_like' => $ad->is_like,
            'user_id' => $ad->user_id,

            'title' => $ad->title,
            'slug' => $ad->slug,
            'image' => getImages($ad->id),
            'address' => getAddress($ad->id),
            'seller' => getSeller($ad->id),
            'category' => $ad->category->title,
            'category_parent' => $ad->root_category_title,
            'price' => $ad->personalAd->price,
            'gender' => $ad->personalAd->gender,
            'personal_ads_type' => $ad->personalAd->type->name,
            'donation' => $ad->personalAd->donation ?? null,
            'check' => $ad->personalAd->check,
            'installments' => $ad->personalAd->installments,
            'cash' => $ad->personalAd->cash,
            'currency_code' => $ad->personalAd?->currency?->code,
            'currency' => $ad->personalAd->currency?->title,
            'condition' => $ad->personalAd->condition,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'text' => $ad->personalAd->text,
            'contact' => [
                'site_massage' => $ad->personalAd->site_massage,
                'my_phone' => $ad->personalAd->my_phone,
                'mobile' => $ad->personalAd->mobile,
            ],



        ];
        return api_response($return);

    }


}
