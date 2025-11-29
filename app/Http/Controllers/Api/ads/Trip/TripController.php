<?php

namespace App\Http\Controllers\Api\ads\Trip;

use App\Http\Controllers\Controller;
use App\Models\Ad;

class TripController extends Controller
{
    public function show($id)
    {

        $ad = Ad::with('trip')->find($id);

        if (!$ad || !$ad->trip) {
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
            'price' => $ad->trip->price,
            'start_date' => $ad->trip->start_date,
            'end_date' => $ad->trip->end_date,
            'weight' => $ad->trip->weight,
            'trip_way' => $ad->trip->trip_way,
            'origin_country' => $ad->trip->origin_country->name,
            'origin_city' => $ad->trip->origin_city->name,
            'destination_country' => $ad->trip->destination_country->name,
            'destination_city' => $ad->trip->destination_city->name,
            'currency_code' => $ad->trip?->currency?->code,
            'currency' => $ad->trip->currency?->title,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'text' => $ad->trip->description,
            'contact' => [
                'site_massage' => $ad->trip->site_massage,
                'my_phone' => $ad->trip->my_phone,
                'mobile' => $ad->trip->mobile,
            ],



        ];
        return api_response($return);

    }


}


