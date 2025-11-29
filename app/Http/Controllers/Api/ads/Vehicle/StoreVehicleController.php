<?php

namespace App\Http\Controllers\Api\ads\Vehicle;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\AdImage;
use App\Models\Currency;
use App\Models\Vehicle\Vehicle;
use App\Models\Vehicle\VehicleBrand;
use App\Models\Vehicle\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreVehicleController extends Controller
{
    public function index(Request $request)
    {

        $c = Currency::select(['id', 'title', 'code'])->get();

        $brand = VehicleBrand::all();
        $q= $request->get('brand');
        $model = VehicleModel::where('brand_id', $q)->get();

        return api_response(
            [
                'currency' => $c,
                'brands' => $brand,
                'models' => $model,
            ]
        );
    }


    public function first(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required',
            'vehicle_brand_id' => 'required',
            'vehicle_model_id' => 'required',
            'function' => 'required',
            'gearbox' => 'required',
            'color' => 'required',
            'date_model' => 'required',
            'type' => 'required',

        ]);

        $ad = Ad::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'type' => $request->type,

        ]);
        Vehicle::create([
            'ad_id' => $ad->id,
            'vehicle_brand_id' => $request->vehicle_brand_id,
            'vehicle_model_id' => $request->vehicle_model_id,
            'function' => $request->function,
            'gearbox' => $request->gearbox,
            'color' => $request->color,
            'date_model' => $request->date_model,
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
            'motor' => 'nullable',
            'chassis_status' => 'nullable',
            'fuel_type' => 'nullable',
            'body' => 'nullable',
            'text' => 'nullable',

        ]);

        $ad = Ad::find($request->ad_id);
        $ad->vehiclesAds()->update([
            'motor' => $request->motor,
            'chassis_status' => $request->chassis_status,
            'body' => $request->body,
            'fuel_type' => $request->fuel_type,
            'text' => $request->text,

        ]);
        return api_response([], __('messages.saved_successfully'));

    }
    public function fourth(Request $request)
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
        $ad->vehiclesAds()->update([
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
            'donation' => 'nullable|numeric',
            'cash' => 'nullable',
            'installments' => 'nullable',
            'check' => 'nullable',
        ]);

        $ad = Ad::find($request->ad_id);
        $ad->vehiclesAds()->update([
            'currencies_id' => $request->currencies_id,
            'price' => $request->price,
            'donation' => $request->donation,
            'cash' => $request->cash,
            'installments' => $request->installments,
            'check' => $request->check,
        ]);
        $ad->update([
            'is_finish' => 1,
        ]);
        return api_response([], __('messages.saved_successfully'));

    }

    public function delete($id)
    {
        $ad = Ad::find($id);

        if (!$ad) {
            return api_response([], __('messages.not_found'), 404);
        }

        foreach ($ad->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
        $ad->delete();

        return api_response([], __('messages.deleted_successfully'));
    }
}
