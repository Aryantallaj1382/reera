<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\Housemate\Housemate;
use App\Models\UserAttribute;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MyAdsController extends Controller
{
    public function index(Request $request)
    {
        $ads = Ad::where('user_id', auth()->id())->where('type' , 'ad');
        if ($request->has('category_id')) {
            $categoryId = $request->category_id;
            $category = Category::with('children')->find($categoryId);
            if ($category) {
                $ids = collect([$category->id])
                    ->merge($category->children->pluck('id'))
                    ->toArray();
                $ads = $ads->whereIn('category_id', $ids);
            }
        }
        if ($request->filled('status')) {
            $ads = $ads->where('status', $request->status);
        }
        $ads = $ads->paginate(6);
        $ads->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => auth()->id(),
                'title' => $item->title,
                'slug' => $item->root_category_title,
                'custom_info' => $item->custom_info,
                'image' => $item->image,
                'status' => $item->status,
                'price' => optional($item->housingAds)->price,
                'time' => $item->time_ago,
                'created_at' => $item?->remaining,
                'location' => $item->location,
                'view' => $item->view,
                'category' => optional($item->category)->title,
                'category_id' => optional($item->category)->id,
            ];
        });

        return api_response($ads);
    }
    public function delete($id)
    {
        $user = auth()->user()->id;
        $ad = Ad::where('user_id', $user)->find($id);
        if (!$ad) {
           return api_response(null, 'Ads not found');
        }
        $ad->delete();
        return api_response([], 'Ads deleted');

    }
    public function extension($id)
    {
        $user = auth()->user()->id;
        $ad = Ad::where('user_id', $user)->find($id);
        if (!$ad) {
            return api_response(null, 'Ads not found');
        }
        $ad->update([
            'created_at' => Carbon::now(),
            'status' => 'approved'
        ]);
        return api_response([], 'Ads update');


    }

    public function sold($id)
    {
        $user = auth()->user()->id;
        $ad = Ad::where('user_id', $user)->find($id);
        if (!$ad) {
            return api_response(null, 'Ads not found');
        }
        $ad->update([
            'status' => 'sold'
        ]);
        return api_response([], 'Ads update');

    }
}
