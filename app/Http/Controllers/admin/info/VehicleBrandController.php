<?php

namespace App\Http\Controllers\admin\info;

use App\Models\Digital\DigitalBrand;
use App\Models\Digital\DigitalModel;
use App\Models\Vehicle\VehicleBrand;
use App\Models\Vehicle\VehicleModel;
use Illuminate\Http\Request;

class VehicleBrandController
{
    public function index()
    {
        $brands = VehicleBrand::orderBy('id', 'desc')->get();
        return view('info.VehicleBrand.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        VehicleBrand::create(['name' => $request->name]);
        return back();
    }

    public function destroy(VehicleBrand $VehicleBrand)
    {
        $VehicleBrand->delete();
        return back();
    }

    public function models(VehicleBrand $brand)
    {
        $models = $brand->models()->orderBy('id', 'desc')->get();
        return view('info.VehicleMdels.index', compact('brand', 'models'));
    }

    public function store_model(Request $request, VehicleBrand $brand)
    {
        $request->validate(['name' => 'required']);
        $brand->models()->create(['name' => $request->name]);
        return back();
    }

    public function destroy_model( $VehicleBrand, $VehicleModel)
    {
        $v = VehicleModel::find($VehicleModel);
        $v->delete();
        return back();
    }

}
