<?php

namespace App\Http\Controllers\Api\ads;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\AdReport;
use App\Models\Category\Category;
use App\Models\Chat;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::query();
        $currency = $request->currency;
        $query->filterCommon($request);
        if ($request->category_slug == 'housing') {
            $query->filterHousing($request);
        } elseif ($request->category_slug == 'vehicles') {
            $query->vehicles($request);
        }
        elseif ($request->category_slug == 'digital') {
            $query->digital($request);
        }

        $ads = $query->latest()->paginate();

        $ads->getCollection()->transform(function ($ad) {
            return [
                'id'          => $ad->id,
                'title'       => $ad->title,
                'time'        => $ad->time_ago,
                'image'       => $ad->image,
                'is_verified' => $ad->is_verified,
                'location'    => $ad->location,
                'category'    => $ad->category->title,
                'custom_info' => $ad->custom_info,
                'root_category_slug' => $ad->root_category_slug,
                'price' => $ad->price,
            ];
        });

        return api_response($ads);
    }

    public function get_filters(Request $request)
    {
        $country_id = $request->input('country_id');
        $category = Category::where('parent_id', null)->get();
        $country = Country::all();
        $city = City::where('country_id', $country_id)->get();
        $currency = Currency::all();
        $min_price = Ad::min('price');
        $max_price = Ad::max('price');

        return api_response([
            'country' => $country,
            'city' => $city,
            'currency' => $currency,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'category' => $category,
        ]);


    }
    public function rates()
    {
        $a = getRates();
        return api_response($a);
    }
    public function toggleLike(Request $request, $id)
    {
        $as = Ad::findOrFail($id);
        $user = auth()->user();

        if (!$user) {
            return api_response([], 'برای لایک کردن لاگین کنید', 401);
        }

        $like = $user->likes()
            ->where('likeable_id', $as->id)
            ->where('likeable_type', Ad::class)
            ->first();

        if ($like) {
            $like->delete();
            return api_response([], 'لایک حذف شد');
        }

        $as->likes()->create([
            'user_id' => $user->id,
        ]);

        return api_response([], 'لایک شد');
    }

    public function convert1(Request $request)
    {

        $a = convert($request->price , $request->from , $request->to );
        return api_response($a);

    }
    public function store(Request $request, $adId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:191',
        ]);

        $ad = Ad::findOrFail($adId);

        AdReport::create([
            'reporter_id' => auth()->id(),
            'ad_id'       => $ad->id,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return api_response([]);
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
    public function request_ad(Request $request , $id)
    {
        $ad = Ad::findOrFail($id);
        $request->validate([
            'text' => 'required',
        ]);
        $chat = Chat::where('user_one_id', auth()->id())->where('ad_id', $id)->first();
        if ($chat) {
            return api_response([], 'شما قبلا برای این درخواست دلده اید' , 422);
        }
        $chat = Chat::create([
            'user_one_id' => auth()->id() ,
            'user_two_id' => $ad->user->id,
            'ad_id'       => $ad->id,
            'status'      => 'pending',

        ]);
        $chat->messages()->create([
           'sender_id' => auth()->id(),
            'message' => $request->text,
        ]);
        return api_response([], 'درخواست ثبت شد');

    }
    public function currency()
    {
        $currency = Currency::all();
        return api_response($currency);

    }
}
