<?php

namespace App\Http\Controllers\admin\kitchen;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Kitchen\KitchenAd;
use Illuminate\Http\Request;


class KitchenController extends Controller
{
    public function index()
    {
       $kitchens=KitchenAd::latest()->paginate(10);
       return view('admin.ads.kitchen.index',compact('kitchens'));
    }
    public function show( string $id)
    {
        $kitchen=KitchenAd::with('ad')->find($id);
        if(!$kitchen){
            abort(404,'Kitchen not found');
        }
        return view('admin.ads.kitchen.show',compact('kitchen'));


    }
    public function updatestatus(Request $request, string $id)
    {
        $request->validate([
            'status'=>'required|in:approved,pending,rejected,sold,'
        ]);
        $kitchen=Ad::find($id);
        $kitchen->update(['status'=>$request->status]);
        return redirect()->back()->with('success','Kitchen status updated successfully');

    }



    public function delete( string $id)
    {
        $kitchen=KitchenAd::find($id);
        $kitchen->delete();
        return redirect()->route('kitchen.index');

    }

    }
