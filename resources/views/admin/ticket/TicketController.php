<?php

namespace App\Http\Controllers\admin\ticket;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Kitchen\KitchenAd;
use App\Models\Ticket;
use App\Models\TicketAd;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = TicketAd::with('ad');

        if (!empty($request->status)) {
            $query->whereHas('ad', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if (!empty($request->title)) {
            $query->whereHas('ad', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->title . '%');
            });
        }


        if (!empty($request->search)) {
            $query->where('text', 'like', '%' . $request->search . '%');
        }


        $tickets = $query->paginate(10)->withQueryString();

        return view('admin.ads.ticket.index',compact('tickets'));
    }
    public function show(string $id){
        $ticket=TicketAd::with('ad')->find($id);
//        dd($ticket->ad?->images->pluck('image_path')->toArray());
        return view('admin.ads.ticket.show',compact('ticket'));
    }
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status'=>'required|in:open,pending,close'
        ]);
        $ticket=Ad::find($id);
        $ticket->status=['status'=>$request->status];
        return redirect()->back()->with('success','Ticket status updated successfully');
    }
    public function destroy(Request $request, string $id)
    {
        $ticket=TicketAd::find($id);
        $ticket->delete();
        return redirect()->back()->with('success','Ticket deleted successfully');
    }
}
