<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class InfoController extends Controller
{
    public function index()
    {
        return view('admin.info.index');
    }

}
