<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\GroupChat;
use Illuminate\Http\Request;

class GroupChatController extends Controller
{
    public function message($id)
    {
        $user = auth()->user();
        $chat = GroupChat::where('country_id' , $id)->latest()->paginate(100);

     $chat->getCollection()->transform(function ($item, $key) use ($user) {
            return [
                'id' => $item->id,
                'message' => $item->message,
                'created_at' => $item->created_at?->format('m/d H:i') ?? null,
                'is_mine' => $item->sender_id == $user->id ? true : false,
            ];
        })->values();
        return api_response($chat);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'country_id' => 'required',
        ]);

        $user = auth()->user();

        GroupChat::create([
            'message'   => $request->message,
            'sender_id' => $user->id,
            'country_id' => $request->country_id,
        ]);

        return api_response([], 'chat sent');
    }


}
