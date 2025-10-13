<?php

namespace App\Http\Controllers\Api\ads\Recruitment;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\AdImage;
use App\Models\BusinessAd;
use App\Models\Digital\DigitalAd;
use App\Models\RecruitmentAd;
use Illuminate\Http\Request;

class UpdateRecruitmentController extends Controller
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
                'languages_id' => $ad->recruitmentAd->languages_id,
                'recruitment_categories_id' => $ad->recruitmentAd->category->id,
                'days' => $ad->recruitmentAd->days,
                'time' => $ad->recruitmentAd->time,
                'price' => $ad->recruitmentAd->price,
                'work_type' => $ad->recruitmentAd->type,
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
                'details' => $ad->recruitmentAd->details,
            ],
            'fourth' =>$ad->images,
            'fifth' => [
                'site_massage' => $ad->recruitmentAd->site_massage,
                'my_phone' => $ad->recruitmentAd->my_phone,
                'other_phone' => $ad->recruitmentAd->other_phone,
                'other_phone_number' => $ad->recruitmentAd->other_phone_number,
            ],
            'sixth' => [
                'currencies_id' =>$ad->recruitmentAd?->currency?->title,
                'degree' => $ad->recruitmentAd->degree,
                'role' => $ad->recruitmentAd->role,
                'skill' => $ad->recruitmentAd->skill,

            ],
            'seventh' => [
                'plan_type' =>$ad->recruitmentAd?->plan_type,


            ],
        ];
        return api_response($return);


    }
    public function first(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'languages_id' => 'required|max:255',
            'recruitment_categories_id' => 'required|max:255',
            'days' => 'required',
            'time' => 'required',
            'price' => 'required',
            'type' => 'required',
            'work_type' => 'required',
        ]);

        $ad = Ad::findOrFail($id);

        $ad->update([
            'title' => $request->title,
            'price' => $request->price,

        ]);

        $RecruitmentAd = RecruitmentAd::where('ad_id', $ad->id)->first();
        if ($RecruitmentAd) {
            $RecruitmentAd->update([
                'languages_id' => $request->languages_id,
                'recruitment_categories_id' => $request->recruitment_categories_id,
                'days' => $request->days,
                'time' => $request->time,
                'price' => $request->price,
                'type' => $request->work_type,
            ]);
        } else {
            RecruitmentAd::create([
                'ad_id'     => $ad->id,
                'languages_id' => $request->languages_id,
                'recruitment_categories_id' => $request->recruitment_categories_id,
                'days' => $request->days,
                'time' => $request->time,
                'price' => $request->price,
                'type' => $request->work_type,
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
            'details' => 'nullable',

        ]);

        $ad = Ad::findOrFail($id);

        $ad->recruitmentAd()->update([
            'details' => $request->details

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

        $ad->recruitmentAd()->update([
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
            'degree' =>'required',
            'skill' => 'required',
            'role' => 'nullable',

        ]);

        $ad = Ad::findOrFail($id);

        $ad->recruitmentAd()->update([
            'degree' => $request->degree,
            'skill' => $request->skill,
            'role' => $request->role,
        ]);

        return api_response([], __('messages.saved_successfully'));
    }
    public function seventh(Request $request , $id)
    {
        $request->validate([
            'plan_type' => 'required',


        ]);

        $ad = Ad::find($id);
        $ad->recruitmentAd()->update([
            'plan_type' => $request->plan_type,


        ]);
        $ad->update([
            'is_finish' => 1,
        ]);
        return api_response([], __('messages.saved_successfully'));

    }

}
