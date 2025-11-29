<?php

namespace App\Http\Controllers\admin\digital\mobile;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdAddress;
use App\Models\Digital\DigitalAd;
use App\Models\Digital\DigitalBrand;
use App\Models\Kitchen\KitchenAd;
use Illuminate\Http\Request;



class DigitalMobileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DigitalAd::with('ad');

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


        $digital = $query->paginate(10)->withQueryString();
        return view('admin.ads.digital.index', compact('digital'));
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


        return view('admin.ads.digital.show', compact('digital'));
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
