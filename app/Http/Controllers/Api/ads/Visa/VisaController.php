<?php

namespace App\Http\Controllers\Api\ads\Visa;

use App\Http\Controllers\Controller;
use App\Models\Ad;

class VisaController extends Controller
{
    public function show($id)
    {

        $ad = Ad::with('visa')->find($id);

        if (!$ad || !$ad->visa) {
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
            'price' => $ad->visa->price,
            'types' => $ad->visa->types,
            'credit' => $ad->visa->credit,
            'Documents' => $ad->visa->Documents,
            'date_of_get_visa' => $ad->visa->date_of_get_visa,
            'origin_country' => $ad->visa?->country?->name,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'text' => $ad->visa->text,
            'contact' => [
                'site_massage' => $ad->visa->site_massage,
                'my_phone' => $ad->visa->my_phone,
                'mobile' => $ad->visa->mobile,
            ],



        ];
        return api_response($return);

    }


}
