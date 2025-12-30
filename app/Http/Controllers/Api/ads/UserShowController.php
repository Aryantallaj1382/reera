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
            'bio' =>'این بیو من است',
            'profile'=> $user->profile,
            'duration'=> $user->membership_duration,
            'ratings'=> $user->ratings_summary,
            'is_iran'=> $user->is_iran,
            'nationality'=> $user?->nationality?->title,
        ]);


    }

    public function user_ads($id)
    {
        $ads = Ad::where('user_id', $id)->get();
        $a = $ads->map(function ($ad) {
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
                'currency' => $ad->currency?->title,
            ];
        });
        return api_response($a);

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

        $comments->getCollection()->transform(function ($comment) {
            return [
                'id' => $comment->id,
                'body' => $comment->body,
                'user' => [
                    'name' => $comment->user->name,
                    'profile' => $comment->user->profile ? asset($comment->user->profile) : asset('default-avatar.png'),
                ],
                'created_at' => $comment->created_at->diffForHumans(), // مثلاً "2 ساعت پیش"
                'average_rating' => $comment->average_rating,
                'likes_count' => $comment->likes_count,
                'is_liked' => $comment->likes->isNotEmpty(), // آیا کاربر فعلی لایک کرده
                'replies' => $comment->replies->map(function ($reply) {
                    return [
                        'id' => $reply->id,
                        'body' => $reply->body,
                        'user' => [
                            'name' => $reply->user->name,
                            'profile' => $reply->user->profile ? asset($reply->user->profile) : asset('default-avatar.png'),
                        ],
                        'created_at' => $reply->created_at->diffForHumans(),
                    ];
                }),
            ];
        });
        return api_response($comments);
    }

}
