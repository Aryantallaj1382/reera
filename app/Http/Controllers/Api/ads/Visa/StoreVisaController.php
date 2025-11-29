<?php

namespace App\Http\Controllers\Api\ads\Visa;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdImage;
use App\Models\Vehicle\Vehicle;
use App\Models\Visa;
use App\Models\VisaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreVisaController extends Controller
{
    public function index()
    {
        $model = VisaType::all();
        return api_response(
            [
                'type' => $model,
            ]
        );
    }

    public function first(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'visa_type_ids' => 'nullable|array', // آرایه‌ای از idهای نوع ویزا
            'visa_type_ids.*' => 'exists:visa_types,id',
        ]);
            $ad = Ad::create([
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'title' => $request->title,
                'type' => $request->type,
            ]);
            $visa = Visa::create([
                'ad_id' => $ad->id,
            ]);
            if ($request->filled('visa_type_ids')) {
                $visa->types()->sync($request->visa_type_ids);
            }


            return api_response($ad->id, __('messages.saved_successfully'));

    }
    public function third(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|integer|exists:ads,id',
            'credit' => 'nullable',
            'text' => 'nullable',
            'Documents' => 'nullable',
            'date_of_get_visa' => 'required',
            'country_id' => 'required',

        ]);

        $ad = Ad::find($request->ad_id);
        $ad->visa()->update([
            'credit' => $request->credit,
            'text' => $request->text,
            'Documents' => $request->Documents,
            'country_id' => $request->country_id,
            'date_of_get_visa' => $request->date_of_get_visa,

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
        $ad->visa()->update([
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
        $ad->visa()->update([
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

}
