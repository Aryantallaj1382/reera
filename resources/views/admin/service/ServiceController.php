<?php

namespace App\Http\Controllers\admin\service;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Kitchen\KitchenAd;
use App\Models\ServicesAd;
//use http\Env\Request;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = ServicesAd::with('ad');

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


       $services = $query->paginate(10)->withQueryString();

        return view('admin.ads.service.index',compact('services'));


    }
    public function show(string $id)
    {
        $service=ServicesAd::with('Ad')->find($id);
        if (!$service) {
            abort(404, 'Service not found');
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
