<?php

namespace App\Http\Controllers\Api\ads;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdsController extends Controller
{
    public function index(Request $request)
    {
        $ads = Ad::latest()->paginate();
        $ads->getCollection()->transform(function ($ad) {
            return[
                'id' => $ad->id,
                'title' => $ad->title,
                'time' => $ad->time_ago,
                'location' => $ad->location,
                'category' => $ad->category->title,

            ];
        });
        return api_response($ads);
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
