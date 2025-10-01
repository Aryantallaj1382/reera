<?php

namespace App\Http\Controllers\Api\Profile;

use App\Models\Ad;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class DashboardController
{

    public function index(Request $request)
    {
        $ads = Ad::where('user_id', auth()->id());

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



        if ($request->filled('type')) {
            $ads = $ads->where('type', $request->type);
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
                'created_at' => $item?->created_at,
                'location' => $item->location,
                'view' => $item->view,
                'category' => optional($item->category)->title,
                'model' => optional($item->category)->slug,
                'category_id' => optional($item->category)->id,
                'remaining' => $item?->remaining,

            ];
        });

        return api_response($ads);
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
        return api_response([], 'Ads sold');

    }
}
