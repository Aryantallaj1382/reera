<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function comment(Request $request)
    {
        $status = $request->query('status'); // می‌تونه 'pending'، 'approved' یا 'rejected' باشه

        $comments = Comment::with(['user', 'replies', 'commentable'])
            ->whereNull('parent_id')
            ->when(in_array($status, ['pending', 'approved', 'rejected']), function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(8);

        return view('admin.comment', compact('comments', 'status'));
    }
    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'approved']);
        return back()->with('success', 'نظر با موفقیت تایید شد.');
    }

    public function reject(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'rejected']);
        return back()->with('success', 'نظر با موفقیت رد شد.');
    }
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'نظر با موفقیت حذف شد.');
    }

}
