<?php

namespace App\Http\Controllers\admin\digital\mobile;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\Digital\DigitalAd;
use App\Models\Digital\DigitalBrand;
use Illuminate\Http\Request;

class DigitalMobileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mobiles = DigitalAd::latest()->paginate(10);

        return view('admin.ads.digital.mobile.index', compact('mobiles'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $digital = DigitalAd::with('ad')->find($id);
        if (!$digital) {
            abort(404, 'آگهی مورد نظر یافت نشد');
        }


        return view('admin.ads.digital.mobile.show', compact('digital'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,sold',
        ]);
        $digital = Ad::find($id);
        $digital->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'وضعیت آگهی بروزرسانی شد.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mobile = DigitalAd::find($id);
        $mobile->delete();
        return redirect()->back()->with('success', 'آگهی حذف شد');
    }
}
