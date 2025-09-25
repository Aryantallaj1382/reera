<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class MyAdsController extends Controller
{
    public function index(Request $request)
    {
        $ads = Ad::where('user_id', auth()->id());

        if ($request->filled('category_id')) {
            $ads = $ads->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $ads = $ads->where('status', $request->status);
        }
        $ads = $ads->paginate(10);
            $ads->getCollection()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'custom_info' => $item->custom_info,
                    'image' => $item->main_image,
                    'status' => $item->status,
                    'price' => optional($item->housingAds)->price,
                    'time' => $item->time_ago,
                    'location' => $item->location,
                    'category' => optional($item->category)->title,
                    'category_id' => optional($item->category)->id,

                ];
            });
        return api_response($ads);
    }
}
