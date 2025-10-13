<?php

namespace App\Http\Controllers\Api\ads\Service;

use App\Models\Ad;

class ServiceController
{
    public function show($id)
    {

        $ad = Ad::with('serviceAds')->find($id);

        if(!$ad->serviceAds)
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
