<?php

namespace App\Http\Controllers\Api\ads\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Ad;

class KitchenController extends Controller
{
    public function show($id)
    {

        $ad = Ad::with('kitchenAds')->find($id);

        if(!$ad->kitchenAds)
        {
            return api_response([], 'wrong id');
        }
        $return =[

            'id' => $ad->id,
            'title' => $ad->title,
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


}
