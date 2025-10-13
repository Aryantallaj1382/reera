<?php

namespace App\Http\Controllers\Api\ads\Housing;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\AdImage;
use App\Models\BusinessAd;
use App\Models\Digital\DigitalAd;
use App\Models\HousingAds\HousingAds;
use Illuminate\Http\Request;

class UpdateHousingController extends Controller
{
    public function get($id)
    {
        $ad = Ad::find($id);
        $return = [
            'slug' => $ad->root_category_slug,
            'first' => [
                'id' => $ad->id,
                'slug' => $ad->root_category_slug,
                'category_id' => $ad->category_id,
                'type' => $ad->type,
                'title' => $ad->title,
                'area' => $ad->housingAds->area,
                'year' => $ad->housingAds->year,
                'number_of_bedrooms' => $ad->housingAds->number_of_bedrooms,
                'number_of_bathroom' => $ad->housingAds->number_of_bathroom,
            ],
            'second' => [
                'country_id'  => $ad->address?->country?->id,
                'city_id'  => $ad->address?->city?->id,
                'region'  => $ad->address->region,
                'full_address'  => $ad->address->full_address,
                'longitude'  => $ad->address->longitude,
                'latitude'  => $ad->address->latitude,
                'distance_from_shopping_center'  => $ad->housingAds->distance_from_shopping_center,
                'distance_from_taxi_stand'  => $ad->housingAds->distance_from_taxi_stand,
                'distance_from_gas_station'  => $ad->housingAds->distance_from_gas_station,
                'distance_from_hospital'  => $ad->housingAds->distance_from_hospital,
                'distance_from_bus_station'  => $ad->housingAds->distance_from_bus_station,
                'distance_from_airport'  => $ad->housingAds->distance_from_airport,

            ],
            'third' => [
                'text' => $ad->housingAds->text,
                'elevator' => $ad->housingAds->elevator,
                'parking' => $ad->housingAds->parking,
                'internet' => $ad->housingAds->internet,
                'pet' => $ad->housingAds->pet,
                'washing_machine' => $ad->housingAds->washing_machine,
                'balcony' => $ad->housingAds->balcony,
                'system' => $ad->housingAds->system,
                'empty' => $ad->housingAds->empty,
                'in_use' => $ad->housingAds->in_use,
                'furnished' => $ad->housingAds->furnished,
                'visit_from' => $ad->housingAds->visit_from,
                'storage' => $ad->housingAds->storage,
                'cooling' => $ad->housingAds->cooling,
                'heating' => $ad->housingAds->heating,
                'open_kitchen' => $ad->housingAds->open_kitchen,
                'cabinets' => $ad->housingAds->cabinets,
                'flooring' => $ad->housingAds->flooring,
                'security_door' => $ad->housingAds->security_door,
                'double_glazed_windows' => $ad->housingAds->double_glazed_windows,
                'security_guard' => $ad->housingAds->security_guard,
                'cctv' => $ad->housingAds->cctv,
                'generator' => $ad->housingAds->generator,
                'master_bedroom' => $ad->housingAds->master_bedroom,
                'guest_hall' => $ad->housingAds->guest_hall,
                'gym' => $ad->housingAds->gym,
                'pool' => $ad->housingAds->pool,


            ],
            'fourth' =>$ad->images,
            'fifth' => [
                'site_massage' => $ad->housingAds->site_massage,
                'my_phone' => $ad->housingAds->my_phone,
                'other_phone' => $ad->housingAds->other_phone,
                'other_phone_number' => $ad->housingAds->other_phone_number,
            ],
            'sixth' => [
                'currencies_id' =>$ad->housingAds?->currency?->title,
                'price' => $ad->housingAds->price,
                'donation' => $ad->housingAds->donation,
                'cash' => $ad->housingAds->cash,
                'installments' => $ad->housingAds->installments,
                'check' => $ad->housingAds->check,
                'family' => $ad->housingAds->family,
                'man' => $ad->housingAds->man,
                'woman' => $ad->housingAds->woman,
                'student' => $ad->housingAds->student,
                'rules' => $ad->housingAds->rules,
            ],
        ];
        return api_response($return);


    }

    public function first(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:255',
            'number_of_bedrooms' => 'nullable',
            'number_of_bathroom' => 'nullable',
            'type' => 'nullable',
        ]);

        $ad = Ad::findOrFail($id);

        $ad->update([
            'title' => $request->title,
        ]);

        $HousingAds = HousingAds::where('ad_id', $ad->id)->first();
        if ($HousingAds) {
            $HousingAds->update([
                'area' => $request->area,
                'year' => $request->year,
                'number_of_bedrooms' => $request->number_of_bedrooms,
                'number_of_bathroom' => $request->number_of_bathroom,
            ]);
        } else {
            HousingAds::create([
                'ad_id'     => $ad->id,
                'area' => $request->area,
                'year' => $request->year,
                'number_of_bedrooms' => $request->number_of_bedrooms,
                'number_of_bathroom' => $request->number_of_bathroom,
            ]);
        }

        return api_response($ad->id, __('messages.updated_successfully'));
    }
    public function second(Request $request, $id)
    {
        $request->validate([
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'region' => 'required|string|max:255',
            'full_address' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'distance_from_shopping_center' => 'required|string|max:255',
            'distance_from_taxi_stand' => 'required|string|max:255',
            'distance_from_gas_station' => 'required|string|max:255',
            'distance_from_hospital' => 'required|string|max:255',
            'distance_from_bus_station' => 'required|string|max:255',
            'distance_from_airport' => 'required|string|max:255',
        ]);

        $ad = Ad::findOrFail($id);
        $ad->address->update([
            'country_id'   => $request->country_id,
            'city_id'      => $request->city_id,
            'region'       => $request->region,
            'full_address' => $request->full_address,
            'longitude'    => $request->longitude,
            'latitude'     => $request->latitude,
        ]);
        $ad->housingAds()->update([
            'distance_from_shopping_center' => $request->distance_from_shopping_center,
            'distance_from_taxi_stand' => $request->distance_from_taxi_stand,
            'distance_from_gas_station' => $request->distance_from_gas_station,
            'distance_from_hospital' => $request->distance_from_hospital,
            'distance_from_bus_station' => $request->distance_from_bus_station,
            'distance_from_airport' => $request->distance_from_airport,

        ]);
        return api_response([], __('messages.updated_successfully'));
    }
    public function third(Request $request , $id)
    {
        $request->validate([
            'elevator' => 'nullable',
            'parking' => 'nullable',
            'furnished' => 'nullable',
            'internet' => 'nullable',
            'pet' => 'nullable',
            'washing_machine' => 'nullable',
            'balcony' => 'nullable',
            'system' => 'nullable',
            'empty' => 'nullable',
            'in_use' => 'nullable',
            'visit_from' => 'nullable',
            'text' => 'nullable',
            'storage' => 'nullable',
            'cooling' => 'nullable',
            'heating' => 'nullable',
            'open_kitchen' => 'nullable',
            'cabinets' => 'nullable',
            'flooring' => 'nullable',
            'security_door' => 'nullable',
            'double_glazed_windows' => 'nullable',
            'security_guard' => 'nullable',
            'cctv' => 'nullable',
            'generator' => 'nullable',
            'master_bedroom' => 'nullable',
            'guest_hall' => 'nullable',
            'gym' => 'nullable',
            'pool' => 'nullable',
            ]);

        $ad = Ad::findOrFail($id);

        $ad->housingAds()->update([
            'elevator' => $request->elevator,
            'parking' => $request->parking,
            'furnished' => $request->furnished,
            'internet' => $request->internet,
            'pet' => $request->pet,
            'washing_machine' => $request->washing_machine,
            'balcony' => $request->balcony,
            'system' => $request->system,
            'empty' => $request->empty,
            'in_use' => $request->in_use,
            'visit_from' => $request->visit_from,
            'text' => $request->text,
            'storage' => $request->storage,
            'cooling' => $request->cooling,
            'heating' => $request->heating,
            'open_kitchen' => $request->open_kitchen,
            'cabinets' => $request->cabinets,
            'double_glazed_windows' => $request->double_glazed_windows,
            'flooring' => $request->flooring,
            'security_door' => $request->security_door,
            'security_guard' => $request->security_guard,
            'cctv' => $request->cctv,
            'generator' => $request->generator,
            'master_bedroom' => $request->master_bedroom,
            'guest_hall' => $request->guest_hall,
            'gym' => $request->gym,
            'pool' => $request->pool,

        ]);

        return api_response([], __('messages.saved_successfully'));
    }
    public function fourth(Request $request , $id)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*.is_main' => 'required|boolean',
        ], [], [
            'ad_id' => 'آگهی',
            'images' => 'عکس‌ها',
            'images.*.image' => 'عکس',
            'images.*.is_main' => 'اصلی بودن',
        ]);

        foreach ($request->images as $img) {
            $file = $img['image'];
            $destinationPath = public_path('ad_images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);

            AdImage::create([
                'ad_id' => $id,
                'image_path' => 'ad_images/' . $fileName,
                'is_main' => $img['is_main'],
            ]);
        }

        return api_response([], __('messages.saved_successfully'));
    }

    public function fifth(Request $request , $id)
    {
        $request->validate([
            'site_massage' => 'nullable',
            'my_phone' => 'nullable',
            'other_phone' => 'nullable',
            'other_phone_number' => 'nullable|string',
        ]);

        $ad = Ad::findOrFail($id);

        $ad->housingAds()->update([
            'site_massage' => $request->site_massage,
            'my_phone' => $request->my_phone,
            'other_phone' => $request->other_phone,
            'other_phone_number' => $request->other_phone_number,
        ]);

        return api_response([], __('messages.saved_successfully'));
    }
    public function sixth(Request $request, $id)
    {
        $request->validate([
            'currencies_id'=> 'required|integer|exists:currencies,id',
            'price'        => 'required|numeric|min:0',
            'cash'         => 'nullable|boolean',
            'installments' => 'nullable|boolean',
            'check'        => 'nullable|boolean',
            'family' => 'nullable',
            'man' => 'nullable',
            'woman' => 'nullable',
            'student' => 'nullable',
            'rules' => 'array|nullable', // نه json
        ]);

        $ad = Ad::findOrFail($id);

        $ad->housingAds()->update([
            'currencies_id' => $request->currencies_id,
            'price' => $request->price,
            'donation' => $request->donation,
            'cash' => $request->cash,
            'installments' => $request->installments,
            'check' => $request->check,
            'family' => $request->family,
            'man' => $request->man,
            'woman' => $request->woman,
            'student' => $request->student,
            'rules' => $request->rules,
        ]);

        return api_response([], __('messages.saved_successfully'));
    }

}
