<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class MyCommentController extends Controller
{
    public function myAdComments()
    {
        $user = auth()->user();

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
    public function myRate()
    {
        $user = auth()->user();

        $r = [
            'owner_behavior' => $user->average_owner_behavior_rating,
            'price_clarity' =>$user->average_price_clarity_rating,
            'info_honesty' =>$user->average_info_honesty_rating,
            'cleanliness' =>$user->average_cleanliness_rating,
            'overall' =>$user->ratings_summary,
        ];
        return api_response($r);
    }

    public function toggleLike(Request $request, $id)
    {
        $group = Comment::findOrFail($id);
        $user = auth()->user();

        if (!$user) {
            return api_response([], 'برای لایک کردن لاگین کنید', 401);
        }

        $like = $user->likes()
            ->where('likeable_id', $group->id)
            ->where('likeable_type', Comment::class)
            ->first();

        if ($like) {
            $like->delete();
            return api_response(['is_like' => false], 'لایک حذف شد');
        }

        $group->likes()->create([
            'user_id' => $user->id,
        ]);

        return api_response(['is_like' => true], 'لایک شد');
    }

}
