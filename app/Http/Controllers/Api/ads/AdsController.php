<?php

namespace App\Http\Controllers\Api\ads;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdsController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::query();

        if ($request->has('category_id')) {
            $categoryId = $request->category_id;

            $category = Category::with('children')->find($categoryId);

            if ($category) {
                $ids = collect([$category->id])
                    ->merge($category->children->pluck('id'))
                    ->toArray();

                $query->whereIn('category_id', $ids);
            }
        }


        $query->when($request->country_id, function ($q) use ($request) {
            $q->whereHas('address', function ($q2) use ($request) {
                $q2->where('country_id', $request->country_id);
            });
        });

        $query->when($request->city_id, function ($q) use ($request) {
            $q->whereHas('address', function ($q2) use ($request) {
                $q2->where('city_id', $request->city_id);
            });
        });

        $query->when($request->region, function ($q) use ($request) {
            $q->whereHas('address', function ($q2) use ($request) {
                $q2->where('region', $request->region);
            });
        });
        $query->when($request->currency, fn($q) => $q->where('currency_id', $request->currency));
        $query->when($request->min_price, fn($q) => $q->where('price', '>=', $request->min_price));
        $query->when($request->max_price, fn($q) => $q->where('price', '<=', $request->max_price));
        $query->when($request->has('is_verified'), fn($q) =>
        $q->where('is_verified', $request->is_verified)
        );
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'expensive':
                $query->orderBy('price', 'desc');
                break;
            case 'cheap':
                $query->orderBy('price', 'asc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $ads = $query->latest()->paginate();

        $ads->getCollection()->transform(function ($ad) {
            return [
                'id' => $ad->id,
                'title' => $ad->title,
                'time' => $ad->time_ago,
                'image' => $ad->image,
                'is_verified' => $ad->is_verified,
                'location' => $ad->location,
                'category' => $ad->category->title,
                'custom_info' => $ad->custom_info,
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
        $response = Http::get('http://api.navasan.tech/latest/', [
            'api_key' => 'free62J4lQnrVxIzehO9oyq0WBX8KTeS',
            'item' => 'harat_naghdi_sell',
        ]);

        $data = $response->json();

        if (!$response->successful() || !isset($data['harat_naghdi_sell'])) {
            return response()->json([
                'message' => 'داده معتبر از API دریافت نشد',
                'data' => $data
            ], 500);
        }

        return response()->json([
            'price' => $data['harat_naghdi_sell']['value'],
            'change' => $data['harat_naghdi_sell']['change'],
            'datetime' => $data['harat_naghdi_sell']['date']
        ]);
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

}
