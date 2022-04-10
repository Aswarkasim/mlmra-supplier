<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Districtess;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    public function insertProvinces() {
        $responses = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/province');
        $responses->json();
        $responses = json_decode($responses, true);
        $no = 0;
        for($i = 1; $i <= 34; $i++ ) {
            $provinces = new Province();
            $provinces->name = $responses['rajaongkir']['results'][$no]['province'];
            $provinces->save();
            $no++;
        }
        return response()->json([
            'message' => "Sukses"
        ]);
    }

    public function insertCities() {
        $responses = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/city');
        $responses->json();
        $responses = json_decode($responses, true);
        $no = 0;
        for($i = 1; $i <= 501; $i++ ) {
            $cities = new City();
            $cities->province_id = $responses['rajaongkir']['results'][$no]['province_id'];
            $cities->name = $responses['rajaongkir']['results'][$no]['city_name'];
            $cities->postal_code = $responses['rajaongkir']['results'][$no]['postal_code'];
            $cities->save();
            $no++;
        }
        return response()->json([
            'message' => "Sukses"
        ]);
    }

    public function insertDistrict() {
        for ($city = 1; $city <= 501; $city++) {
            $responses = Http::withHeaders([
                'key' => env('RAJAONGKIR_API')
            ])->get('https://pro.rajaongkir.com/api/subdistrict', [
                'city' => $city,
            ]);
            $responses->json();
            $responses = json_decode($responses, true);
            $totalDistrictEveryCity = count($responses['rajaongkir']['results']);
            $no = 0;
            foreach ($totalDistrictEveryCity as $total) {
                $answers[] = [
                    'city_id' => $responses['rajaongkir']['results'][$no]['city_id'],
                    'subdistrict_name' => $responses['rajaongkir']['results'][$no]['subdistrict_name'],
                ];
                $no++;
                if (!$total) {
                    District::insert($answers);
                }
            }
        }
        return response()->json([
            'message' => "Sukses"
        ]);
    }
}


//$answers[] = [
//    'city_id' => $responses['rajaongkir']['results'][$no]['city_id'],
//    'name' => $responses['rajaongkir']['results'][$no]['subdistrict_name'],
//];
//District::insert($answers);

