<?php

namespace App\Http\Controllers\admin\info;

use App\Http\Controllers\Controller;
use App\Models\Kitchen\KitchenBrand;
use Illuminate\Http\Request;

class KitchenBrandController extends Controller
{
    public function index()
    {
        $brands = KitchenBrand::orderBy('id', 'desc')->get();
        return view('info.KitchenBrand.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        KitchenBrand::create(['name' => $request->name]);
        return back();
    }



    public function destroy(KitchenBrand $KitchenBrand)
    {
        try {
            $KitchenBrand->delete();
            return back()->with('success', 'حذف شد');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


}
