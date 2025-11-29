<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        return view('admin.dashboard');
    }
}
