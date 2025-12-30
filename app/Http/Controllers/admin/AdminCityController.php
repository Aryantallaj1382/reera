<?php

namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class AdminCityController extends Controller
{
    public function show(Country $country)
    {
        $cities = $country->cities;
        return view('admin.cities.index', compact('country', 'cities'));
    }

    public function store(Request $request, Country $country)
    {
        $request->validate(['name' => 'required']);
        $country->cities()->create(['name' => $request->name]);
        return back();
    }

    public function update(Request $request, City $city)
    {
        $request->validate(['name' => 'required']);
        $city->update(['name' => $request->name]);
        return back();
    }

    public function destroy(City $city)
    {
        $city->delete();
        return back();
    }
}
