<?php

namespace App\Http\Controllers\Api\ads;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\User;

class UserShowController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);
        return api_response([
            'name' => $user->first_name.' '.$user->last_name,
            'profile'=> $user->profile,
            'duration'=> $user->membership_duration,
            'ratings'=> $user->ratings_summary,
            'is_iran'=> $user->is_iran,
            'nationality'=> $user->nationality->title,
        ]);


    }

    public function user_ads($id)
    {
        $ads = Ad::where('user_id', $id)->get();
        $ads->map(function ($ad) {
            return[
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
                'currency' => $ad->currency->title,
            ];
        });
        return api_response($ads);

    }
    public function rate($id)
    {
        $user = User::find($id);
        $r = [
            'owner_behavior' => $user->average_owner_behavior_rating,
            'price_clarity' =>$user->average_price_clarity_rating,
            'info_honesty' =>$user->average_info_honesty_rating,
            'cleanliness' =>$user->average_cleanliness_rating,
            'overall' =>$user->ratings_summary,
        ];
        return api_response($r);
    }
    public function user_Comments($id)
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

}
