<?php

namespace App\Http\Controllers\Api\ads\Digital;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\AdImage;
use App\Models\Category\Category;
use App\Models\Currency;
use App\Models\Digital\DigitalAd;
use App\Models\Digital\DigitalBrand;
use App\Models\Digital\DigitalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreDigitalController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('children.children')->whereNull('parent_id')->get();

        $result = $categories->map(function ($category) {
            $format = function ($cat) use (&$format) {
                return [
                    'id' => $cat->id,
                    'title' => $cat->title,
                    'slug' => $cat->slug,
                    'children' => $cat->children->map(function ($child) use (&$format) {
                        return $format($child);
                    }),
                ];
            };

            return $format($category);
        });

        $c = Currency::select('id', 'title', 'code')->get();

        $brand = DigitalBrand::all();
        $q= $request->get('brand');
        $model = DigitalModel::where('brand_id', $q)->get();

        return api_response(
            [
                'categories' => $result,
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
                'title' => 'required|string|max:255',
                'digital_brand_id' => 'required|max:255',
                'digital_model_id' => 'required|max:255',
                'view_time' => 'required',
                'condition' => 'required',
                'type' => 'required',
            ]);

            $ad = Ad::create([
                'user_id' => 1,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'type' => $request->type,
            ]);
            DigitalAd::create([
                'ad_id' => $ad->id,
                'digital_brand_id' => $request->digital_brand_id,
                'digital_model_id' => $request->digital_model_id,
                'view_time' => $request->view_time,
                'condition' => $request->condition,
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
            'phone_case' => 'nullable',
            'glass' => 'nullable',
            'stand' => 'nullable',
            'cable' => 'nullable',
            'text' => 'nullable',

        ]);

        $ad = Ad::find($request->ad_id);
        $ad->digitalAd()->update([
            'phone_case' => $request->phone_case,
            'glass' => $request->glass,
            'stand' => $request->stand,
            'cable' => $request->cable,
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
            $path = $img['image']->store('ad_images', 'public');

            AdImage::create([
                'ad_id' => $data['ad_id'],
                'image_path' => $path,
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
        $ad->digitalAd()->update([
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
            'deposit' => 'required|numeric|min:0',
            'cash' => 'nullable',
            'installments' => 'nullable',
            'check' => 'nullable',

        ]);

        $ad = Ad::find($request->ad_id);
        $ad->digitalAd()->update([
            'currencies_id' => $request->currencies_id,
            'price' => $request->price,
            'deposit' => $request->deposit,
            'cash' => $request->cash,
            'installments' => $request->installments,
            'check' => $request->check,

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
