<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function userTickets()
    {
        $tickets = Ticket::with('messages')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate();
        $tickets->getCollection()->transform(function ($ticket) {
            return [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'subject' => $ticket->subject,
                'department' => $ticket->department,
                'status' => $ticket->status,

            ];
        });

        return api_response($tickets);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required',
            'department' => 'required',
            'message' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'ticket_number' => strtoupper(Str::random(10)),
            'user_id' => auth()->id(),
            'subject' => $data['subject'],
            'department' => $data['department'],
            'status' => 'open',
        ]);

        $ticket->messages()->create([
            'message' => $data['message'],
            'is_support' => false,
        ]);

        return api_response($ticket->id);
    }

    public function addMessage(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $data = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->messages()->create($data);

        return api_response();
    }

    public function show($id)
    {
        $ticket = Ticket::with('messages')->findOrFail($id);
        $return =[
            'id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'subject' => $ticket->subject,
            'department' => $ticket->department,
            'status' => $ticket->status,
            'count' => $ticket->messages->count(),
            'messages' => $ticket->messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'is_support' => $message->is_support,
                ];
            }),

        ] ;

        return api_response($return);
    }
}
