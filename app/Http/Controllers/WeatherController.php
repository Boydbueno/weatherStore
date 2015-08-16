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

        // We grab the latest data from this lat and long
        $rawData = file_get_contents('http://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lon);

        if (!$rawData) {
            // We failed to retrieve data from the webservice
            // Just return the stuff we have
            return $weather->where('lat', $lat)->where('lon', $lon)->limit(10)->get();
        }

        $jsonData = json_decode($rawData, true);

        // We transform this data
        $data = [
            'dt' => $jsonData['dt'],
            'lat' => $lat,
            'lon' => $lon,
            'type' => $jsonData['weather'][0]['main'],
            'temp' => $jsonData['main']['temp'] - 273.15,
        ];

        // Check if we already have a record with same lat/lon and dt in our database
        $weatherCheck = $weather->where('lat', $lat)->where('lon', $lon)->where('dt', Carbon::createFromTimeStamp($data['dt']))->limit(1)->get();

        // Record isn't in our db yet
        if ($weatherCheck->isEmpty()) {
            // We store the data in our database
            $weather->create($data);
        }

        // We grab the last 10 weather report from given lat/lon and return it
        return $weather->where('lat', $lat)->where('lon', $lon)->limit(10)->get();
    }
}
