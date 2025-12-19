<?php

namespace App\Http\Controllers\Api\ads\Service;

use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\Kitchen\KitchenAd;
use App\Models\PersonalAdType;
use App\Models\ServiceExpertise;
use App\Models\ServicesAd;
use Illuminate\Http\Request;

class ServiceController
{
    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'services')->with('children')->first();
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

        $lang = Ad::whereRelation('category', 'slug', 'services')->with('address')->get();
        $loc = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();
        $a = ServiceExpertise::all();


        $minPrice =ServicesAd::min('price');
        $maxPrice =ServicesAd::max('price');

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

        $ad = Ad::with('serviceAds')->find($id);
        $ad->increment('view');

        if(!$ad->serviceAds)
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
            'price' => $ad->serviceAds->price,
            'donation' => $ad->serviceAds->donation,
            'time_service' => $ad->serviceAds->time,
            'expertise' => $ad->serviceAds->expertise->name,
            'check' => $ad->serviceAds->check,
            'installments' => $ad->serviceAds->installments,
            'cash' => $ad->serviceAds->cash,
            'currency_code' => $ad->serviceAds?->currency?->code,
            'currency' => $ad->serviceAds->currency?->title,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'text' => $ad->serviceAds->text,
            'contact' => [
                'site_massage' => $ad->serviceAds->site_massage,
                'my_phone' => $ad->serviceAds->my_phone,
                'mobile' => $ad->serviceAds->mobile,
            ],



        ];
        return api_response($return);

    }


}
