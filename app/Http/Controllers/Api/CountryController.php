<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CountryController extends Controller
{
    public function getCountries(Request $request)
    {
        $country_id = $request->query('country');

        if ($country_id) {
            // $city = Cache::remember("cities_of_country_$country_id",  now()->addDays(30), function () use ($country_id) {
            //     return City::where('country_id', $country_id)
            //         ->select('id', 'name')
            //         ->get();
            // });

            $city = City::where('country_id', $country_id)
                ->select('id', 'name')
                ->get();
        }

        // $countries = Cache::remember('countries_list', now()->addDays(30), function () {
        //     return Country::select('id', 'name')->get();
        // });

        $countries = Country::select('id', 'name')->get();

        return api_response([
            'countries' => $countries,
            'city' => $city ?? [],
        ]);
    }

}
