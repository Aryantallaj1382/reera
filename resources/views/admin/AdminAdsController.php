<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAdsController extends Controller
{
    public function index()
    {
        return view('admin.ads.index');
    }
    public function show()
    {
        return view('admin.ads.show');
    }
}
