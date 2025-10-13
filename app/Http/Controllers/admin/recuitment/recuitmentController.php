<?php

namespace App\Http\Controllers\admin\recuitment;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\RecruitmentAd;
use Illuminate\Http\Request;
class recuitmentController extends Controller
{
    public function index()
    {
        $recuiments=RecruitmentAd::with('ad')->latest()->paginate(10);
        if(!$recuiments){
            abort(404,'recuiments not found');

        }
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
