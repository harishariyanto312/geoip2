<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GeoIp2\Database\Reader;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userIP = $request->query('ip', request()->ip());

        try {
            $reader = new Reader(database_path('GeoLite2-Country.mmdb'));
            $record = $reader->country($userIP);
            
            $result = [
                'IP' => $userIP,
                'countryISOCode' => $record->country->isoCode,
                'country' => $record->country->name,
                'continent' => $record->continent->names['en'],
            ];
        }
        catch (\Throwable $e) {
            $result = [
                'IP' => $userIP,
                'countryISOCode' => 'XX',
                'country' => 'XX',
                'continent' => 'XX',
            ];
        }

        return response()->json($result, 200, [], JSON_PRETTY_PRINT);
    }
}