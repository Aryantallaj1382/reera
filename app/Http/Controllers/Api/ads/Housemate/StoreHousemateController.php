<?php

namespace App\Http\Controllers\Api\ads\Housemate;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\AdImage;
use App\Models\Category\Category;
use App\Models\Currency;
use App\Models\Housemate\Housemate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreHousemateController extends Controller
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

        return api_response(
            [
                'categories' => $result,
                'currency' => $c,
            ]
        );
    }

    public function first(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'number_of_bedrooms' => 'required',
            'number_of_bathroom' => 'required',
            'type' => 'required',

        ]);

        $ad = Ad::create([
            'user_id' => 1,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'type' => $request->type,

        ]);
        Housemate::create([
            'ad_id' => $ad->id,
            'area' => $request->area,
            'year' => $request->year,
            'number_of_bedrooms' => $request->number_of_bedrooms,
            'number_of_bathroom' => $request->number_of_bathroom,
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
            'distance_from_shopping_center' => 'required|string|max:255',
            'distance_from_taxi_stand' => 'required|string|max:255',
            'distance_from_gas_station' => 'required|string|max:255',
            'distance_from_hospital' => 'required|string|max:255',
            'distance_from_bus_station' => 'required|string|max:255',
            'distance_from_airport' => 'required|string|max:255',
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
        $ad->housemate()->update([
            'distance_from_shopping_center' => $request->distance_from_shopping_center,
            'distance_from_taxi_stand' => $request->distance_from_taxi_stand,
            'distance_from_gas_station' => $request->distance_from_gas_station,
            'distance_from_hospital' => $request->distance_from_hospital,
            'distance_from_bus_station' => $request->distance_from_bus_station,
            'distance_from_airport' => $request->distance_from_airport,

        ]);
        return api_response([], __('messages.saved_successfully'));

    }
    public function third(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|integer|exists:ads,id',
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

        $ad = Ad::find($request->ad_id);
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
        $ad->housemate()->update([
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

        $ad = Ad::find($request->ad_id);
        $housemate = $ad->housemate;

        $housemate->update([
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
        if ($request->has('traits')) {
            $housemate->personalTraits()->delete();

            foreach ($request->traits as $trait) {
                $housemate->personalTraits()->create([
                    'trait' => $trait['trait'],
                    'number' => $trait['number'],
                ]);
            }
        }

        if ($request->has('traits1')) {
            $housemate->personalTraits()->delete();

            foreach ($request->traits as $trait) {
                $housemate->personalTraits()->create([
                    'trait' => $trait['trait'],
                    'number' => null,
                ]);
            }
        }
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
