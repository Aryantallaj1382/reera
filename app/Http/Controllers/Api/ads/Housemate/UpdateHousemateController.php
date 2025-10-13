<?php

namespace App\Http\Controllers\Api\ads\Housemate;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\AdImage;
use App\Models\BusinessAd;
use App\Models\Digital\DigitalAd;
use App\Models\Housemate\Housemate;
use Illuminate\Http\Request;

class UpdateHousemateController extends Controller
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
                'area' => $ad->housemate->area,
                'year' => $ad->housemate->year,
                'number_of_bedrooms' => $ad->housemate->number_of_bedrooms,
                'number_of_bathroom' => $ad->housemate->number_of_bathroom,
                'condition' => $ad->businessAd->condition,
            ],
            'second' => [
                'country_id'  => $ad->address?->country?->id,
                'city_id'  => $ad->address?->city?->id,
                'region'  => $ad->address->region,
                'full_address'  => $ad->address->full_address,
                'longitude'  => $ad->address->longitude,
                'latitude'  => $ad->address->latitude,
                'distance_from_shopping_center'  => $ad->housemate->distance_from_shopping_center,
                'distance_from_taxi_stand'  => $ad->housemate->distance_from_taxi_stand,
                'distance_from_gas_station'  => $ad->housemate->distance_from_gas_station,
                'distance_from_hospital'  => $ad->housemate->distance_from_hospital,
                'distance_from_bus_station'  => $ad->housemate->distance_from_bus_station,
                'distance_from_airport'  => $ad->housemate->distance_from_airport,

            ],
            'third' => [
                'text' => $ad->housemate->text,
                'elevator' => $ad->housemate->elevator,
                'parking' => $ad->housemate->parking,
                'internet' => $ad->housemate->internet,
                'pet' => $ad->housemate->pet,
                'washing_machine' => $ad->housemate->washing_machine,
                'balcony' => $ad->housemate->balcony,
                'system' => $ad->housemate->system,
                'empty' => $ad->housemate->empty,
                'in_use' => $ad->housemate->in_use,
                'visit_from' => $ad->housemate->visit_from,

            ],
            'fourth' =>$ad->images,
            'fifth' => [
                'site_massage' => $ad->housemate->site_massage,
                'my_phone' => $ad->housemate->my_phone,
                'other_phone' => $ad->housemate->other_phone,
                'other_phone_number' => $ad->housemate->other_phone_number,
            ],
            'sixth' => [
                'currencies_id' =>$ad->housemate?->currency?->title,
                'price' => $ad->housemate->price,
                'donation' => $ad->housemate->donation,
                'cash' => $ad->housemate->cash,
                'installments' => $ad->housemate->installments,
                'check' => $ad->housemate->check,
                'family' => $ad->housemate->family,
                'man' => $ad->housemate->man,
                'woman' => $ad->housemate->woman,
                'student' => $ad->housemate->student,
                'rules' => $ad->housemate->rules,
                'traits' => $ad->housemate->personalTraits,
            ],
        ];
        return api_response($return);


    }

    public function first(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'number_of_bedrooms' => 'required',
            'number_of_bathroom' => 'required',
        ]);

        $ad = Ad::findOrFail($id);

        $ad->update([
            'title'       => $request->title,
        ]);

        $Housemate = Housemate::where('ad_id', $ad->id)->first();
        if ($Housemate) {
            $Housemate->update([
                'ad_id' => $ad->id,
                'area' => $request->area,
                'year' => $request->year,
                'number_of_bedrooms' => $request->number_of_bedrooms,
                'number_of_bathroom' => $request->number_of_bathroom,
            ]);
        } else {
            Housemate::create([
                'ad_id' => $ad->id,
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
        $ad->housemate()->update([
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
            ]);

        $ad = Ad::findOrFail($id);

        $ad->housemate()->update([
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

        $ad->digitalAd()->update([
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
            'currencies_id' =>'required',
            'price' => 'required|numeric|min:0',
            'donation' => 'required|numeric|min:0',
            'cash' => 'nullable',
            'installments' => 'nullable',
            'check' => 'nullable',
            'family' => 'nullable',
            'man' => 'nullable',
            'woman' => 'nullable',
            'student' => 'nullable',
            'rules' => 'array|nullable',
            'traits' => 'array|nullable',
            'traits.*.trait' => 'required_with:traits|string',
            'traits.*.number' => 'required_with:traits|integer',
            'traits1' => 'array|nullable',
            'traits1.*.trait' => 'required_with:traits|string',
            'traits1.*.number' => 'nullable',
        ]);

        $ad = Ad::findOrFail($id);

        $ad->digitalAd()->update([
            'currencies_id' => $request->currencies_id,
            'price'         => $request->price,
            'cash'          => $request->cash,
            'installments'  => $request->installments,
            'check'         => $request->check,
        ]);

        return api_response([], __('messages.saved_successfully'));
    }

}
