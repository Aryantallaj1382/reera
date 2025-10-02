<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category\Category;
use App\Models\UserAttribute;
use Illuminate\Http\Request;

class RequestController extends Controller

{
    public function index(Request $request)
    {
        $ads = Ad::where('user_id', auth()->id())->where('type' , 'request');
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
                'created_at' => $item?->remaining,
                'location' => $item->location,
                'view' => $item->view,
                'category' => optional($item->category)->title,
                'category_id' => optional($item->category)->id,
            ];
        });

        return api_response($ads);
    }


}
