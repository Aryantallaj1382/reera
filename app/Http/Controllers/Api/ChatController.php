<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Str;

class ChatController extends Controller
{
//    public function index(Request $request)
//    {
//        $request->validate([
//            'ad_id' => 'required|exists:ads,id',
//            'message' => 'required|string',
//        ]);
//        $chat = Chat::updateOrCreate()
//
//    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $chats = Chat::where(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)
                ->orWhere('user_two_id', $user->id);
        });
        if ($request->sort === 'my_ads') {
            $chats->whereRelation('ad', 'user_id', $user->id);
        }

        if ($request->sort === 'other_ads') {
            $chats->whereRelation('ad', 'user_id', '!=', $user->id);
        }

        if ($request->sort === 'no_seen') {
            $chats->whereHas('messages', function ($query) {
                $query->where('is_seen', 0);
            });
        }
       $chat = $chats->paginate();
        $chat->getCollection()->transform(function ($item, $key) use ($user) {
            $otherUser = $item->user_one_id == $user->id
                ? $item->userTwo
                : $item->userOne;
            $lastMessage = $item->messages
                ->sortByDesc('created_at')
                ->first();
            return [
                'name' => $otherUser->first_name . ' ' . $otherUser->last_name,
                'profile' => $otherUser->profile,
                'id' => $item->id,

                'created_at' => $lastMessage?->created_at?->format('m/d') ?? null,
                'last_message' => $lastMessage
                    ? Str::limit($lastMessage->message, 20)
                    : null,
                'ads_name' => $item->ad?->title,
                'image' => $item->ad?->image,

            ];
        });
        return api_response($chat);
    }
    public function message($id)
    {
        $user = auth()->user();
        $chat = Chat::where(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)
                ->orWhere('user_two_id', $user->id);
        })->find($id);
        $otherUser = $chat->user_one_id == $user->id
            ? $chat->userTwo
            : $chat->userOne;
        if (!$chat) {
            api_response([],'ops');
        }
        $chat->messages()->where('sender_id', '!=' , $user->id)->update(['is_seen' => 1]);
        $message = $chat->messages()->get();
        $m  = $message->sortBy('created_at')->map(function ($item, $key) use ($user) {
            return [
                'id' => $item->id,
                'message' => $item->message,
                'created_at' => $item->created_at?->format('m/d') ?? null,
                'is_seen' => $item->is_seen,
                'file' => $item->file,
                'is_mine' => $item->sender_id == $user->id ? true : false,
            ];
        })->values();
        return response()->json([
            'data'=>$m,
            'profile' => $otherUser->profile,
            'user_id' => $otherUser->id,
            'name' => $otherUser->first_name . ' ' . $otherUser->last_name,
            'ads_image' => $chat->ad?->image,
            'status' => $chat->status,
            'id' => $chat->id,
            'ads_name' => $chat->ad?->title,

        ]);

    }
    public function block($id)
    {
        $user = auth()->user();
        $chat = Chat::where(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)
                ->orWhere('user_two_id', $user->id);
        })->find($id);
        if (!$chat) {
            api_response([],'ops');
        }
        $chat->update(['status' => 'blocked']);
        return api_response([],'chat blocked');
    }
    public function unblock($id)
    {
        $user = auth()->user();
        $chat = Chat::where(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)
                ->orWhere('user_two_id', $user->id);
        })->find($id);
        if (!$chat) {
            api_response([],'ops');
        }
        $chat->update(['status' => 'active']);
        return api_response([],'chat active');
    }
    public function send(Request $request, $id)
    {
        $request->validate([
            'message' => 'nullable',
            'file' => 'nullable|file|max:10240' // حداکثر 10MB (اختیاری)
        ]);

        $user = auth()->user();

        $chat = Chat::where(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)
                ->orWhere('user_two_id', $user->id);
        })->find($id);

        if (!$chat) {
            return api_response([], 'ops', 404);
        }

        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/chat'), $fileName);
            $filePath = 'uploads/chat/' . $fileName;
        }

        $chat->messages()->create([
            'message'   => $request->message,
            'sender_id' => $user->id,
            'is_seen'   => 0,
            'file'      => $filePath
        ]);

        return api_response([], 'chat sent');
    }

}
