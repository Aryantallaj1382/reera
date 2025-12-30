<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdApprovalController extends Controller
{

    public function index()
    {
        $ads = Ad::with(['user', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.ads.pending', compact('ads'));
    }


    public function approve(Ad $ad)
    {
        $ad->update([
            'status' => 'approved', // یا 1، بسته به ساختار جدولت
            'rejected_reason' => null, // دلیل رد رو پاک کن
        ]);

        return back()->with('success', 'آگهی با موفقیت تایید شد.');
    }

    public function reject(Request $request, Ad $ad)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:500',
        ]);

        $ad->update([
            'status' => 'rejected', // یا 0 یا 'rejected'
            'rejected_reason' => $request->rejected_reason,
        ]);

        return back()->with('success', 'آگهی رد شد و دلیل ثبت گردید.');
    }
}
