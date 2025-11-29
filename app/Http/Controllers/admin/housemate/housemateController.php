<?php

namespace App\Http\Controllers\admin\housemate;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Housemate\Housemate;
use Illuminate\Http\Request;

class housemateController extends Controller
{
    public function index(Request $request){
        $query=Housemate::with('ad');

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

        $housemate = $query->paginate(10)->withQueryString();
        return view('admin.ads.housemate.index',compact('housemate'));
    }
    public function show(string $id){
        $housemate = Housemate::with('ad')->find($id);
        if (!$housemate) {
            abort(404,'housemate not found');
        }
        return view('admin.ads.housemate.show',compact('housemate'));

    }
    public function updateStatus(Request $request , string $id){
            $request->validate([
                'status' => 'required|in:approved,sold,rejected,pending',
            ]);
            $housemate=Ad::find($id);
            $housemate->update(['status'=>$request->status,]);
            return redirect()->back()->with('success','status updated successfully');

    }
    public function destroy(Request $request , string $id){
        $housemate=Housemate::find($id);
        $housemate->delete();
        return redirect()->back()->with('success','Housemate deleted successfully');
    }
}
