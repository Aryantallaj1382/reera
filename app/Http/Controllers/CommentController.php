<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'body' => 'required|string',
            'id' => 'nullable|integer',
            'parent_id' => 'nullable|integer|exists:comments,id',
            'owner_behavior_rating' => 'nullable|integer|min:1|max:5',
            'price_clarity_rating' => 'nullable|integer|min:1|max:5',
            'info_honesty_rating' => 'nullable|integer|min:1|max:5',
            'cleanliness_rating' => 'nullable|integer|min:1|max:5',
            'status' => 'nullable|in:approved,pending,rejected',
        ]);

        $data['user_id'] = auth()->user()->id;

            Comment::create([
            'body' => $data['body'],
            'commentable_type' => Ad::class,
            'commentable_id' => $data['id'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'owner_behavior_rating' => $data['owner_behavior_rating'] ?? null,
            'price_clarity_rating' => $data['price_clarity_rating'] ?? null,
            'info_honesty_rating' => $data['info_honesty_rating'] ?? null,
            'user_id' => $data['user_id'],


        ]);

        return response()->json([
            'message' => 'کامنت با موفقیت ثبت شد.',
        ], 201);
    }
}
