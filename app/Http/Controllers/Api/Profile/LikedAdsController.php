<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class LikedAdsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $likedAds = Ad::whereHas('likes', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with('category')
            ->get();

        $a = $likedAds->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'category' => optional($item->category)->title,
                'custom_info' => $item->custom_info,
                'image' => $item->main_image,
                'location' => $item->location,

            ];
        });

        return api_response($a);
    }

}
