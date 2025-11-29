<?php

namespace App\Http\Controllers\admin\vehicle;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Digital\DigitalAd;
use App\Models\Kitchen\KitchenAd;
use App\Models\Vehicle\Vehicle;
//use http\Env\Request;
use Illuminate\Http\Request;


class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with('ad');

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

        $vehicles = $query->paginate(10)->withQueryString();

        return view('admin.ads.vehicle.index',compact('vehicles'));

    }


    public function show(string $id)
    {
        $vehicle=Vehicle::with('ad')->find($id);
        if(!$vehicle){
            abort(404,'Vehicle not found');
        }
        return view('admin.ads.vehicle.show',compact('vehicle'));


    }


    public function updatestatus(Request $request, string $id)
    {
        $request->validate([
            'status'=>'required|in:pending,approved,rejected,sold'
        ]);
        $vehicle=Ad::find($id);
        $vehicle->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'وضعیت آگهی بروزرسانی شد.');

    }


    public function destroy(string $id)
    {
        $vehicle = Vehicle::find($id);
        $vehicle->delete();
        return redirect()->route('vehicle.index');
    }
}
