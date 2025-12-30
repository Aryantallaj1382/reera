<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class AdminCountryController extends Controller
{
    public function index()
    {
        $countries = Country::withCount('cities')->get();
        return view('admin.countries.index', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Country::create($request->only('name'));
        return back();
    }

    public function update(Request $request, Country $country)
    {
        $request->validate(['name' => 'required']);
        $country->update($request->only('name'));
        return back();
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return back();
    }
}

