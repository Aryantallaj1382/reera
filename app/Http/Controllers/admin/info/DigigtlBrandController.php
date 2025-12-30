<?php

namespace App\Http\Controllers\admin\info;

use App\Models\Digital\DigitalBrand;
use App\Models\Digital\DigitalModel;
use Illuminate\Http\Request;

class DigigtlBrandController
{
    public function index()
    {
        $brands = DigitalBrand::orderBy('id', 'desc')->get();
        return view('info.DigitalBrands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        DigitalBrand::create(['name' => $request->name]);
        return back();
    }

    public function destroy(DigitalBrand $DigitalBrand)
    {
        $DigitalBrand->delete();
        return back();
    }

    public function models(DigitalBrand $brand)
    {
        $models = $brand->models()->orderBy('id', 'desc')->get();
        return view('info.DigitalMdels.index', compact('brand', 'models'));
    }

    public function store_model(Request $request, DigitalBrand $brand)
    {
        $request->validate(['name' => 'required']);
        $brand->models()->create(['name' => $request->name]);
        return back();
    }

    public function destroy_model(DigitalBrand $brand, DigitalModel $model)
    {
        $model->delete();
        return back();
    }
}
