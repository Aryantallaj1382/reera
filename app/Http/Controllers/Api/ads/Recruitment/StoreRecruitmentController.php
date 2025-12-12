<?php

namespace App\Http\Controllers\Api\ads\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\AdImage;
use App\Models\Category\Category;
use App\Models\Currency;
use App\Models\Language;
use App\Models\PersonalAd;
use App\Models\PersonalAdType;
use App\Models\RecruitmentAd;
use App\Models\RecruitmentCategory;
use Illuminate\Http\Request;
class StoreRecruitmentController extends Controller
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

        $a = RecruitmentCategory::all();
        $l = Language::all();

        $c = Currency::select('id', 'title', 'code')->get();

        return api_response(
            [
                'currency' => $c,
                'category' => $a,
                'language' => $l,
                'price' => 30
            ]
        );
    }
    public function first(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255',
            'languages_id' => 'required|max:255',
            'recruitment_categories_id' => 'required|max:255',
            'days' => 'required',
            'time' => 'required',
            'price' => 'required',
            'type' => 'required',
            'work_type' => 'required',

        ]);

        $ad = Ad::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'type' => $request->type,
            'price' => $request->price,

        ]);
        RecruitmentAd::create([
            'ad_id' => $ad->id,
            'languages_id' => $request->languages_id,
            'recruitment_categories_id' => $request->recruitment_categories_id,
            'days' => $request->days,
            'time' => $request->time,
            'price' => $request->price,
            'type' => $request->work_type,
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
                'custom_facilities' => 'nullable',


        ]);

        $ad = Ad::find($request->ad_id);
        $ad->recruitmentAd()->update([
            'details' => $request->custom_facilities

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
        $ad->recruitmentAd()->update([
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
            'degree' =>'required',
            'skill' => 'required',
            'role' => 'nullable',


        ]);


        $ad = Ad::find($request->ad_id);
        $ad->recruitmentAd()->update([
            'degree' => $request->degree,
            'skill' => $request->skill,
            'role' => $request->role,


        ]);
        return api_response([], __('messages.saved_successfully'));

    }
    public function seventh(Request $request)
    {
        $request->validate([
            'ad_id' => 'required',
            'plan_type' => 'required',


        ]);

        $ad = Ad::find($request->ad_id);
        $ad->recruitmentAd()->update([
            'plan_type' => $request->plan_type,


        ]);
        $ad->update([
            'is_finish' => 1,
            'price'=> $request->price,
            'currencies_id' => $request->currencies_id,

        ]);
        return api_response([], __('messages.saved_successfully'));

    }

}
