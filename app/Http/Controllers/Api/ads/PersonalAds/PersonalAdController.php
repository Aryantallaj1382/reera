<?php

namespace App\Http\Controllers\Api\ads\PersonalAds;

use App\Models\Ad;

class PersonalAdController
{
    public function show($id)
    {

        $ad = Ad::with('personalAd')->find($id);

        if(!$ad->personalAd)
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
            'price' => $ad->personalAd->price,
            'gender' => $ad->personalAd->gender,
            'personal_ads_type' => $ad->personalAd->type->name,
            'donation' => $ad->personalAd->donation,
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
