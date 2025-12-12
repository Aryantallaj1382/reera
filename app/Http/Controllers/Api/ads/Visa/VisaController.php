<?php

namespace App\Http\Controllers\Api\ads\Visa;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\Visa;
use App\Models\VisaType;
use Illuminate\Http\Request;

class VisaController extends Controller
{
    public function get_filters(Request $request)
    {
        $mainCategory = Category::where('slug', 'visa')->with('children')->first();
        if (!$mainCategory) {
            return api_response([], 'دسته‌بندی اصلی پیدا نشد', false);
        }
        $mainChildren = $mainCategory->children->map(function ($child) {
            return [
                'id' => $child->id,
                'category' => $child->title,
            ];
        });
        $extraChildren = [];
        if ($request->filled('category_id')) {
            $selectedCategory = Category::where('id', $request->category_id)->with('children')->first();

            if ($selectedCategory) {
                $extraChildren = $selectedCategory->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'category' => $child->title,
                    ];
                });
            }
        }

        $lang = Ad::whereRelation('category', 'slug', 'visa')->with('address')->get();
        $a = $lang->filter(fn($item) => $item->address)->map(function ($item) {
            return [
                'latitude' => $item->address->latitude,
                'longitude' => $item->address->longitude,
            ];
        })->values();
        $model = VisaType::all();



        $minPrice =Visa::min('price');
        $maxPrice =Visa::max('price');
        return api_response([
            'main_category' => $mainChildren,
            'selected_category' => $extraChildren,
            'type' => $model,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'loc' =>$a
        ]);
    }

    public function show($id)
    {

        $ad = Ad::with('visa')->find($id);

        if (!$ad || !$ad->visa) {
            return api_response([], 'wrong id');
        }
        $return =[
            'id' => $ad->id,
            'title' => $ad->title,
            'is_like' => $ad->is_like,
            'user_id' => $ad->user_id,

            'slug' => $ad->slug,
            'image' => getImages($ad->id),
            'address' => getAddress($ad->id),
            'seller' => getSeller($ad->id),
            'category' => $ad->category->title,
            'category_parent' => $ad->root_category_title,
            'price' => $ad->visa->price,
            'types' => $ad->visa->types,
            'credit' => $ad->visa->credit,
            'Documents' => $ad->visa->Documents,
            'date_of_get_visa' => $ad->visa->date_of_get_visa,
            'origin_country' => $ad->visa?->country?->name,
            'location' => $ad->location,
            'time' => $ad->time_ago,
            'membership' => $ad->user->membership_duration,
            'text' => $ad->visa->text,
            'contact' => [
                'site_massage' => $ad->visa->site_massage,
                'my_phone' => $ad->visa->my_phone,
                'mobile' => $ad->visa->mobile,
            ],



        ];
        return api_response($return);

    }


}
