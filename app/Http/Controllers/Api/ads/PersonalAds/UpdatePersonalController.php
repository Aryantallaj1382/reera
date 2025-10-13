<?php

namespace App\Http\Controllers\Api\ads\PersonalAds;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\AdImage;
use App\Models\BusinessAd;
use App\Models\Digital\DigitalAd;
use App\Models\PersonalAd;
use Illuminate\Http\Request;

class UpdatePersonalController extends Controller
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
                'phone_case' => $ad->personalAd->condition,
                'text' => $ad->personalAd->text,
            ],
            'fourth' =>$ad->images,
            'fifth' => [
                'site_massage' => $ad->personalAd->site_massage,
                'my_phone' => $ad->personalAd->my_phone,
                'other_phone' => $ad->personalAd->other_phone,
                'other_phone_number' => $ad->personalAd->other_phone_number,
            ],
            'sixth' => [
                'currencies_id' =>$ad->personalAd?->currency?->title,
                'price' => $ad->personalAd->price,
                'donation' => $ad->personalAd->donation,
                'cash' => $ad->personalAd->cash,
                'installments' => $ad->personalAd->installments,
                'check' => $ad->personalAd->check,
            ],
        ];
        return api_response($return);


    }
    public function first(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'personal_ads_type_id' => 'required|max:255',
            'gender' => 'required',
        ]);

        $ad = Ad::findOrFail($id);

        $ad->update([
            'title' => $request->title,
        ]);

        $PersonalAd = PersonalAd::where('ad_id', $ad->id)->first();
        if ($PersonalAd) {
            $PersonalAd->update([
                'personal_ads_type_id' => $request->personal_ads_type_id,
                'gender' => $request->gender,
            ]);
        } else {
            PersonalAd::create([
                'ad_id'     => $ad->id,
                'personal_ads_type_id' => $request->personal_ads_type_id,
                'gender' => $request->gender,
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
            'text' => 'nullable',
            ]);

        $ad = Ad::findOrFail($id);

        $ad->digitalAd()->update([
            'condition' => $request->condition,
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
            'currencies_id'=> 'required|integer|exists:currencies,id',
            'price'        => 'required|numeric|min:0',
            'donation' => 'nullable|numeric|min:0',
            'cash'         => 'nullable|boolean',
            'installments' => 'nullable|boolean',
            'check'        => 'nullable|boolean',
        ]);

        $ad = Ad::findOrFail($id);

        $ad->digitalAd()->update([
            'currencies_id' => $request->currencies_id,
            'price'         => $request->price,
            'donation'         => $request->donation,
            'cash'          => $request->cash,
            'installments'  => $request->installments,
            'check'         => $request->check,
        ]);

        return api_response([], __('messages.saved_successfully'));
    }

}
