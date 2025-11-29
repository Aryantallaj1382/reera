<?php

namespace App\Http\Controllers\Api\ads\Housemate;

use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\HousingAds\HousingAds;
use Illuminate\Http\Request;

class HousemateController
{

    public function show($id)
    {

        $ad = Ad::with('housemate')->find($id);
        if (auth()->user())
        {
            $compatibility =  calculateCompatibilityPrecise($ad->housemate->id , 1);
        }
        if(!$ad->housemate)
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
            'compatibility' => $compatibility ??"برای مشخص شدن وارد حسابتان شوید",
            'category_parent' => $ad->root_category_title,
            'price' => $ad->housemate->price,
            'currency_code' => $ad->housemate?->currency?->code,
            'currency' => $ad->housemate->currency?->title,
            'area' => $ad->housemate->area,
            'is_house' => false,

            'number_of_bedrooms' => $ad->housemate->number_of_bedrooms,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'year' => $ad->housemate->year,
            'text' => $ad->housemate->text,
            'family' => $ad->housemate->family,
            'woman' => $ad->housemate->woman,
            'distance' => [
                'distance_from_shopping_center' => $ad->housemate->distance_from_shopping_center,
                'distance_from_taxi_stand' => $ad->housemate->distance_from_taxi_stand,
                'distance_from_gas_station' => $ad->housemate->distance_from_gas_station,
                'distance_from_hospital' => $ad->housemate->distance_from_hospital,
                'distance_from_bus_station' => $ad->housemate->distance_from_bus_station,
                'distance_from_airport' => $ad->housemate->distance_from_airport,
            ] ,
            'man' => $ad->housemate->man,
            'student' => $ad->housemate->student,
            'rules' => $ad->housemate->rules,
            'attributes'=> $ad->user->attributes->pluck('value')->toArray(),
            'contact' => [
                'site_massage' => $ad->housemate->site_massage,
                'my_phone' => $ad->housemate->my_phone,
                'mobile' => $ad->housemate->mobile,
            ],
            'use' => $ad->housemate->use,
            'facilities'=> [
                'elevator' => $ad->housemate->elevator,
                'parking' => $ad->housemate->parking,
                'furnished' => $ad->housemate->furnished,
                'internet' => $ad->housemate->internet,
                'pet' => $ad->housemate->pet,
                'washing_machine' => $ad->housemate->washing_machine,
                'balcony' => $ad->housemate->balcony,
                'system' => $ad->housemate->system,
                'empty' => $ad->housemate->empty,
                'in_use' => $ad->housemate->in_use,
                'visit_from' => $ad->housemate->visit_from,

            ],


        ];
        return api_response($return);

    }

    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'housing')->with('children')->first();
        if (!$mainCategory) {
            return api_response([], 'دسته‌بندی اصلی پیدا نشد', false);
        }
        $mainChildren = $mainCategory->children->map(function ($child) {
            return [
                'id' => $child->id,
                'category' => $child->title,
                'title_en' => $child->title_en,

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

        $lang = Ad::where('category_id', 1)->with('address')->get();
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


}
