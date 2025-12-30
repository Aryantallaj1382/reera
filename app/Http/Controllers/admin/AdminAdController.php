<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;

class AdminAdController extends Controller
{
    public function show(Ad $ad)
    {
        return view('admin.ads.show', compact('ad'));
    }





}
