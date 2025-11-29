<?php

namespace App\Http\Controllers\Api\ads\Trip;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdImage;
use App\Models\TicketAd;
use App\Models\Trip;
use Illuminate\Http\Request;

class StoreTripController extends Controller
{
    public function first(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required',
            'type' => 'required',
            'price' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'weight' => 'required',
            'trip_way' => 'required',
            'text' => 'required',
            'origin_country_id' => 'required',
            'origin_city_id' => 'required',
            'destination_country_id' => 'required',
            'destination_city_id' => 'required',
            'site_massage' => 'nullable',
            'my_phone' => 'nullable',
            'other_phone' => 'nullable',
            'other_phone_number' => 'nullable',
            'currencies_id' => 'nullable',
        ]);
        $ad = Ad::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'type' => $request->type,
        ]);
        Trip::create([
            'ad_id' => $ad->id,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'weight' => $request->weight,
            'trip_way' => $request->trip_way,
            'description' => $request->text,
            'origin_country_id' => $request->origin_country_id,
            'origin_city_id' => $request->origin_city_id,
            'destination_country_id' => $request->destination_country_id,
            'destination_city_id' => $request->destination_city_id,
            'site_massage' => $request->site_massage,
            'my_phone' => $request->my_phone,
            'other_phone' => $request->other_phone,
            'currencies_id' => $request->currencies_id,
            'other_phone_number' => $request->other_phone_number,
        ]);

        return api_response($ad->id, __('messages.saved_successfully'));

    }
    public function second(Request $request)
    {
        $data = $request->validate([
            'ad_id' => 'required|exists:ads,id',
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
            $file = $img['image']; // فایل آپلود شده
            $destinationPath = public_path('ad_images');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            AdImage::create([
                'ad_id' => $data['ad_id'],
                'image_path' => 'ad_images/' . $fileName,
                'is_main' => $img['is_main'],
            ]);
        }

        return api_response([], __('messages.saved_successfully'));
    }
}
