<?php

namespace App\Http\Controllers\Api\ads\Vehicle;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdImage;
use App\Models\Kitchen\KitchenAd;
use App\Models\Vehicle\Vehicle;
use Illuminate\Http\Request;

class UpdateVehicleController extends Controller
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
                'vehicle_model_id' => $ad->vehiclesAds->vehicle_model_id,
                'vehicle_brand_id' => $ad->vehiclesAds->vehicle_brand_id,
                'function' => $ad->vehiclesAds->function,
                'gearbox' => $ad->vehiclesAds->gearbox,
                'color' => $ad->vehiclesAds->color,
                'date_model' => $ad->vehiclesAds->date_model,
            ],
            'second' => [
                'country_id'  => $ad->address?->country?->id,
                'city_id'  => $ad->address?->city?->id,
                'region'  => $ad->address->region,
                'full_address'  => $ad->address->full_address,
                'longitude'  => $ad->address->longitude,
                'latitude'  => $ad->address->latitude,

            ],
            'third' => [
                'body' => $ad->vehiclesAds->body,
                'motor' => $ad->vehiclesAds->motor,
                'chassis_status' => $ad->vehiclesAds->chassis_status,
                'fuel_type' => $ad->vehiclesAds->fuel_type,
                'text ' => $ad->vehiclesAds->text,
            ],
            'fourth' =>$ad->images,
            'fifth' => [
                'site_massage' => $ad->vehiclesAds->site_massage,
                'my_phone' => $ad->vehiclesAds->my_phone,
                'other_phone' => $ad->vehiclesAds->other_phone,
                'other_phone_number' => $ad->vehiclesAds->other_phone_number,
            ],
            'sixth' => [
                'currencies_id' =>$ad->vehiclesAds?->currency?->title,
                'price' => $ad->vehiclesAds->price,
                'donation' => $ad->vehiclesAds->donation,
                'cash' => $ad->vehiclesAds->cash,
                'installments' => $ad->vehiclesAds->installments,
                'check' => $ad->vehiclesAds->check,
            ],
        ];
        return api_response($return);


    }

    public function first(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable',
            'vehicle_brand_id' => 'nullable',
            'vehicle_model_id' => 'nullable',
            'function' => 'nullable',
            'gearbox' => 'nullable',
            'color' => 'nullable',
            'date_model' => 'nullable',
            'type' => 'nullable',
        ]);

        $ad = Ad::findOrFail($id);

        $ad->update([
            'title'       => $request->title,
        ]);

        $KitchenAd = Vehicle::where('ad_id', $ad->id)->first();
        if ($KitchenAd) {
            $KitchenAd->update([
                'vehicle_brand_id' => $request->vehicle_brand_id,
                'vehicle_model_id' => $request->vehicle_model_id,
                'function' => $request->function,
                'gearbox' => $request->gearbox,
                'color' => $request->color,
                'date_model' => $request->date_model,

            ]);
        } else {
            Vehicle::create([
                'ad_id' => $ad->id,
                'vehicle_brand_id' => $request->vehicle_brand_id,
                'vehicle_model_id' => $request->vehicle_model_id,
                'function' => $request->function,
                'gearbox' => $request->gearbox,
                'color' => $request->color,
                'date_model' => $request->date_model,
            ]);
        }

        return api_response($ad->id, __('messages.updated_successfully'));
    }
    public function second(Request $request, $id)
    {
        $request->validate([
            'country_id'   => 'required|integer|exists:countries,id',
            'city_id'      => 'required|integer|exists:cities,id',
            'region'       => 'required|string|max:255',
            'full_address' => 'required|string|max:255',
            'longitude'    => 'required|string|max:255',
            'latitude'     => 'required|string|max:255',
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

        return api_response([], __('messages.updated_successfully'));
    }
    public function third(Request $request , $id)
    {
        $request->validate([
            'condition' => 'nullable',
            'text ' => 'nullable',
            'motor' => 'nullable',
            'chassis_status' => 'nullable',
            'fuel_type' => 'nullable',
            'body' => 'nullable',
        ]);

        $ad = Ad::findOrFail($id);

        $ad->vehiclesAds()->update([
            'text' => $request->text ,
            'motor' => 'nullable',
            'chassis_status' => 'nullable',
            'fuel_type' => 'nullable',
            'body' => 'nullable',
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

        $ad->vehiclesAds()->update([
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
        ]);

        $ad = Ad::findOrFail($id);

        $ad->vehiclesAds()->update([
            'currencies_id' => $request->currencies_id,
            'price' => $request->price,
            'donation' => $request->donation,
            'cash' => $request->cash,
            'installments' => $request->installments,
            'check' => $request->check,
        ]);

        return api_response([], __('messages.saved_successfully'));
    }

}
