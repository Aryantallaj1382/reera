<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Chat;
class ChatController extends Controller
{
    public function myAds(Request $request)
    {
        $ads = Ad::where('user_id', auth()->id())
            ->withCount('chats') ;
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
        $ads = $ads->paginate(10);
        $ads->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'slug' => $item->slug,
                'is_verified' => $item->is_verified,
                'custom_info' => $item->custom_info,
                'image' => $item->main_image,
                'status' => $item->status,
                'location' => $item->location,
                'category' => optional($item->category)->title,
                'category_id' => optional($item->category)->id,
            ];
        });
        return api_response($ads);
    }
    public function adChats($adId)
    {
        $chats = Chat::where('ad_id', $adId)
            ->where(function ($q) {
                $q->where('user_two_id', auth()->id());
            })

            ->first();
        if ($chats) {
            $data = [
                'id'    => $chats->id,
                'ad_id' => $chats->ad_id,
                'name' => $chats->userOne->first_name,
                'date' => $chats->created_at?->format('Y,m,d'),
                'status' => $chats->status,

            ];
        } else {
            $data = null;
        }
        return api_response($data);
    }
    public function accept($id)
    {
        $chats = Chat::
            where(function ($q) {
                $q->where('user_two_id', auth()->id());
            })->find($id);
        $chats->update([
            'status' => 'active',
        ]);
        return api_response([],'accept');

    }
    public function rejected($id)
    {
        $chats = Chat::
        where(function ($q) {
            $q->where('user_two_id', auth()->id());
        })->find($id);
        $chats->update([
            'status' => 'rejected',
        ]);
        return api_response([],'rejected');

    }
}
