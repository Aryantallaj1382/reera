<?php

namespace App\Http\Controllers\Api\ads\Vehicle;

use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\Digital\DigitalAd;
use App\Models\Vehicle\Vehicle;
use App\Models\Vehicle\VehicleBrand;
use App\Models\Vehicle\VehicleModel;
use Illuminate\Http\Request;

class VehicleController
{
    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'vehicles')->with('children')->first();
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

        $lang = Ad::whereRelation('category', 'slug', 'vehicles')->with('address')->get();
        $a = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();

        $brand = VehicleBrand::all();
        $q= $request->get('brand');
        $model = VehicleModel::where('brand_id', $q)->get();


        $minPrice = Vehicle::min('price');
        $maxPrice = Vehicle::max('price');
        $maxFunction = Vehicle::max('function');
        $minDate = Vehicle::max('date_model');
        return api_response([
            'minDate' => $minDate,
            'maxFunction' => $maxFunction,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'main_category' => $mainChildren,
            'selected_category' => $extraChildren,
            'brands' => $brand,
            'models' => $model,
            'loc' =>$a
        ]);
    }

    public function show($id)
    {

        $ad = Ad::with('vehiclesAds')->find($id);
        $ad->increment('view');

        if(!$ad->vehiclesAds)
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
            'function' => $ad->vehiclesAds->function,
            'gearbox' => $ad->vehiclesAds->gearbox,
            'color' => $ad->vehiclesAds->color,
            'chassis_status' => $ad->vehiclesAds->chassis_status,
            'fuel_type' => $ad->vehiclesAds->fuel_type,
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
