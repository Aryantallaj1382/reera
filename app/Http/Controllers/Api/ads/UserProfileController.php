<?php

namespace App\Http\Controllers\Api\ads;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController
{
    public function ads($id)
    {
        $ads = Ad::where('user_id', $id)->paginate(10);
        $ads->getCollection()->transform(function ($item, $key) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'time' => $item->time_ago,
                'image' => $item->image,
                'is_verified' => $item->is_verified,
                'price' => $item->price,
                'status' => $item->status,
                'location' => $item->location,
                'custom_info' => $item->custom_info,
                'category' => $item->category->title,
                'category_root' => $item->root_category_title,
            ];
        });
        return api_response($ads, 'Ads list');


    }
    public function rate($id)
    {
        $user = \App\Models\User::find($id);
        return api_response([
            'owner_behavior' => $user->average_owner_behavior_rating,
            'price_clarity'  => $user->average_price_clarity_rating,
            'info_honesty'   => $user->average_info_honesty_rating,
            'ratings_summary' => $user->ratings_summary,

        ], 'User rate');

    }
    public function comments($id)
    {
        $user = User::find($id);

        $comments = $user->adComments()
            ->with(['user', 'parent']) // برای نمایش نویسنده کامنت و ریپلای‌ها
            ->latest()
            ->paginate(10);
        $comments->getCollection()->transform(function ($item, $key) use ($user)  {
            return [
                'id' => $item->id,
                'rate'  => $item->average_rating,
                'body'  => $item->body,
                'created_at'  => $item->created_at,
                'user_name'  => $item->user->first_name . ' ' . $item->user->last_name,
                'profile'  => $item->profile,
                'is_like' =>$item->is_liked,

            ];
        });
        return api_response($comments);
    }
    public function user_info($id)
    {
        $user = User::find($id);
        return api_response([
            'language' => $user?->language?->title,
            'national' => $user->nationality?->title,
            'age' => $user->age,
            'gender' => $user->gender,
            'job' => $user->job,
        ]);

    }
    public function user_attributes($id)
    {
        $user = User::find($id);
        return api_response($user->attributes?->pluck('value')->toArray());

    }
}
