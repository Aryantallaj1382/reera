<?php

namespace App\Http\Controllers\admin\business;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\BusinessAd;
use App\Models\RecruitmentAd;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index(Request $request){
        $query=BusinessAd::with('ad');
        if(!empty($request->status)){
            $query->whereHas('ad',function($q) use($request){
                $q->where('status',$request->status);
            });


        }
        if(!empty($request->title)){
            $query->whereHas('ad',function($q) use($request){
                $q->where('title','like','%'.$request->title.'%'.$request->title.'%');

            });

        }
        if(!empty($request->search)){
            $query->whereHas('ad',function($q) use($request){
                $q->where('title','like','%'.$request->search.'%');

            });
        };
        $businesses=$query->latest()->paginate(10);
        return view('admin.ads.business.index',compact('businesses'));

    }


    public function show(string $id){
        $business=BusinessAd::with('ad')->find($id);
        if(!$business){
            abort(404,'Business not found');
        }
        return view('admin.ads.business.show',compact('business'));
    }


    public function updateStatus(Request $request,string $id){
        $request->validate([
            'status'=>'required|in:approve,rejected,sold,pending'
        ]);
        $business=Ad::find($id);
        $business->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status updated successfully');
    }


    public function destroy(Request $request, string $id){
        $business=BusinessAd::find($id);
        $business->delete();
        return redirect()->back()->with('success', 'Business deleted successfully');
    }
}
