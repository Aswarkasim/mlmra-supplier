<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    public function getCities($id)
    {
        $city = City::where('province_id', $id)->pluck('name', 'id');
        return response()->json($city);
    }

    public function getDistricts($id)
    {
        $responses = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/subdistrict', [
            'city' => $id,
        ]);
        $responses->json();
        $responses = json_decode($responses, true);
        $no = 0;
        foreach ($responses['rajaongkir']['results'] as $response) {
            $answer[] = [$response['city_id'] => $response['subdistrict_name'], 0 => $response['subdistrict_id']];
            $no++;
        }
        return response()->json($answer);
    }

}
