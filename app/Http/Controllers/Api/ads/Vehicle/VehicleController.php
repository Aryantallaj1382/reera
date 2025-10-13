<?php

namespace App\Http\Controllers\Api\ads\Vehicle;

use App\Models\Ad;

class VehicleController
{
    public function show($id)
    {

        $ad = Ad::with('vehiclesAds')->find($id);

        if(!$ad->vehiclesAds)
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
            'function' => $ad->vehiclesAds->function,
            'gearbox' => $ad->vehiclesAds->gearbox,
            'color' => $ad->vehiclesAds->color,
            'chassis_status' => $ad->vehiclesAds->chassis_status,
            'motor' => $ad->vehiclesAds->motor,
            'body' => $ad->vehiclesAds->body,
            'date_model' => $ad->vehiclesAds->date_model,
            'price' => $ad->vehiclesAds->price,
            'brand' => $ad->vehiclesAds->brand?->name,
            'model' => $ad->vehiclesAds->model?->name,
            'donation' => $ad->vehiclesAds->donation,
            'check' => $ad->vehiclesAds->check,
            'installments' => $ad->vehiclesAds->installments,
            'cash' => $ad->vehiclesAds->cash,
            'currency_code' => $ad->vehiclesAds?->currency?->code,
            'currency' => $ad->vehiclesAds->currency?->title,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'text' => $ad->vehiclesAds->text,
            'contact' => [
                'site_massage' => $ad->vehiclesAds->site_massage,
                'my_phone' => $ad->vehiclesAds->my_phone,
                'mobile' => $ad->vehiclesAds->mobile,
            ],



        ];
        return api_response($return);

    }


}
