<?php

namespace App\Http\Controllers\Api\ads\Trip;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class TripController extends Controller
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
        $a = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();



        return api_response([
            'main_category' => $mainChildren,
            'selected_category' => $extraChildren,

            'loc' =>$a
        ]);
    }

    public function show($id)
    {

        $ad = Ad::with('trip')->find($id);

        if (!$ad || !$ad->trip) {
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


