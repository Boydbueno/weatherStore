<?php

namespace App\Http\Controllers;

use App\Weather;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class WeatherController extends BaseController
{
    public function retrieve(Request $request, Weather $weather)
    {
        if (!$request->has('lat') || !$request->has('lon')) {
            return response('Please provide a lat and lon', 400);
        }

        $input = $request->all();

        // We grab the lat and lon
        $lat = $input['lat'];
        $lon = $input['lon'];

        // We grab the last 10 weather report from given lat/lon and return it
        return $weather->where('lat', $lat)->where('lon', $lon)->limit(10)->get();
    }
}
