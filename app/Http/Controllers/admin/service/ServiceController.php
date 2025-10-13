<?php

namespace App\Http\Controllers\admin\service;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\ServicesAd;
//use http\Env\Request;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services=ServicesAd::with('Ad')->latest()->paginate(10);
        return view('admin.ads.service.index',compact('services'));



    }
    public function show(string $id)
    {
        $service=ServicesAd::with('Ad')->find($id);
        if (!$service) {
            abort(404, 'ServiceController not found');
        }
        return view('admin.ads.service.show',compact('service'));
    }
    public  function updateStatus(Request $request ,string $id)
    {
        $request->validate([
            'status'=>'required|in:sold,pending,rejected,approved'
        ]);
        $service=Ad::find($id);
        $service->update(['status' => $request->status]);
        return redirect()->back()->with('success','با موفقیت آپدیت شد');


    }
    public function destroy(string $id)
    {
        $service=ServicesAd::find($id);
        $service->delete();
        return redirect()->back()->with('success','با موفقیت حذف شد');
    }


}
