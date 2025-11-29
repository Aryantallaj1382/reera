<?php

namespace App\Http\Controllers\admin\real_astate;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\HousingAds\HousingAds;
use Illuminate\Http\Request;

class RealEstateController extends Controller
{
    public function index(Request $request)
    {
        $query = HousingAds::with('ad');


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


        $realEstates = $query->paginate(10)->withQueryString();
        return view('admin.ads.realEstate.index', compact('realEstates'));

    }




    public function show( string $id)
    {
        $realEstate = HousingAds::with('ad')->find($id);
        if (!$realEstate) {
            abort(404, 'page not found');
        }
        return view('admin.ads.realEstate.show', compact('realEstate'));
    }



    public function updateStatus(Request $request, string $id ){
        $request->validate([
            'status' => 'required|in:approved,rejected,sold,pending',
        ]);
        $realEstate = Ad::find($id);
        $realEstate->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status updated successfully');
    }



    public function destroy(Request $request, string $id ){
        $realEstate = HousingAds::find($id);
        $realEstate->delete();
        return redirect()->back()->with('success', 'Status deleted successfully');
    }


}
