<?php

namespace App\Http\Controllers\admin\ads;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdsController extends Controller
{
public function index(){
  return view('admin.ads.index');

}
}
