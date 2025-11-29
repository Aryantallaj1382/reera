<?php

namespace App\Http\Controllers\admin\recuitment;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Digital\DigitalAd;
use App\Models\Kitchen\KitchenAd;
use App\Models\RecruitmentAd;
use Illuminate\Http\Request;
class recuitmentController extends Controller
{
    public function index(Request $request)
    {
        $query = RecruitmentAd::with('ad');

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


        $recuiments = $query->paginate(10)->withQueryString();
        return view('admin.ads.recuitment.index',compact('recuiments'));

    }
    public function show(string $id){
        $recuiment=RecruitmentAd::with('ad')->find($id);
        if(!$recuiment){
            abort(404,'recuiment not found');

        }
        return view('admin.ads.recuitment.show',compact('recuiment'));
    }
    public function updateStatus(Request $request,string $id)
    {
        $request->validate([
            'status'=>'required|in:approved,rejected,sold,pending'
        ]);
        $recuitment=Ad::find($id);
        $recuitment->update(['status'=>$request->status]);
        return redirect()->back()->with('success','recuitment status updated successfully');
    }
    public function destroy(String $id)
    {
        $recuitment=RecruitmentAd::find($id);
        $recuitment->delete();
        return redirect()->back->with('success','recuitment deleted successfully');
    }
}
