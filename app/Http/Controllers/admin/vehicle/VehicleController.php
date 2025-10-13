<?php

namespace App\Http\Controllers\admin\vehicle;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Vehicle\Vehicle;
//use http\Env\Request;
use Illuminate\Http\Request;


class VehicleController extends Controller
{
    public function index(){
        $vehicles=Vehicle::latest()->paginate(10);
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
