<?php

namespace App\Http\Controllers\Api\ads\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\AdImage;
use App\Models\Kitchen\KitchenAd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreKitchenController extends Controller
{
    public function first(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required',
            'kitchen_brand_id' => 'required',
            'kitchen_type_id' => 'required',
        ]);

        $ad = Ad::create([
            'user_id' => 1,
            'category_id' => $request->category_id,
            'title' => $request->title,
        ]);
        KitchenAd::create([
            'ad_id' => $ad->id,
            'kitchen_brand_id' => $request->kitchen_brand_id,
            'kitchen_type_id' => $request->kitchen_type_id,

        ]);

        return api_response($ad->id, __('messages.saved_successfully'));

    }

    public function second(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|integer|exists:ads,id',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'region' => 'required|string|max:255',
            'full_address' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',

        ]);
        $ad = Ad::find($request->ad_id);
        AdAddress::create([
            'ad_id' => $ad->id,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'region' => $request->region,
            'full_address' => $request->full_address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ]);

        return api_response([], __('messages.saved_successfully'));

    }
    public function third(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|integer|exists:ads,id',
            'condition' => 'nullable',
            'text' => 'nullable',

        ]);

        $ad = Ad::find($request->ad_id);
        $ad->kitchenAds()->update([
            'condition' => $request->condition,
            'text' => $request->text,

        ]);
        return api_response([], __('messages.saved_successfully'));

    }

    public function fourth(Request $request)
    {
        $data = $request->validate([
            'ad_id' => 'required|exists:ads,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [], [
            'ad_id' => 'آگهی',
            'images' => 'عکس‌ها',
            'image' => 'عکس اصلی',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('ad_images', 'public');
                AdImage::create([
                    'ad_id' => $data['ad_id'],
                    'image_path' => $path,
                    'is_main' => false,
                ]);
            }
        }

        if ($request->hasFile('image')) {
            $mainImage = $request->file('image');
            $mainPath = $mainImage->store('ad_images', 'public');
            AdImage::create([
                'ad_id' => $data['ad_id'],
                'image_path' => $mainPath,
                'is_main' => true,
            ]);
        }

        return api_response([], __('messages.saved_successfully'));
    }
    public function fifth(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|integer|exists:ads,id',
            'site_massage' => 'nullable',
            'my_phone' => 'nullable',
            'other_phone' => 'nullable',
            'other_phone_number' => 'nullable',
        ]);

        $ad = Ad::find($request->ad_id);
        $ad->kitchenAds()->update([
            'site_massage' => $request->site_massage,
            'my_phone' => $request->my_phone,
            'other_phone' => $request->other_phone,
            'other_phone_number' => $request->other_phone_number,
        ]);

        return api_response([], __('messages.saved_successfully'));
    }

    public function sixth(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|integer|exists:ads,id',
            'currencies_id' =>'required',
            'price' => 'required|numeric|min:0',
            'donation' => 'required|numeric|min:0',
            'cash' => 'nullable',
            'installments' => 'nullable',
            'check' => 'nullable',
        ]);

        $ad = Ad::find($request->ad_id);
        $ad->kitchenAds()->update([
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
