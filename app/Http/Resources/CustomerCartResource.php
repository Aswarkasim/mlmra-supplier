<?php

namespace App\Http\Resources;

use App\Models\Address;
use App\Models\Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CustomerCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function getAddress() {
        $productId = Cart::whereCustomerId(Auth::guard('customer-api')->id())->first();
        if (!$productId) {
            return response()->json([
                'status' => "failed",
                'message' => "Tidak ada produk dalam keranjang!"
            ], 200);
        }
        $mainAddress = Address::whereUserId($productId->product_reseller->product->user->id)->first();
        if (!$mainAddress) {
            return response()->json([
                'status' => "failed",
                'message' => "Harap lengkapi alamat anda!"
            ], 200);
        }
        $city = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/city', ['id' => $mainAddress->city_id]);
        $city->json();
        $city = json_decode($city, true);
        $district = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/subdistrict', ['id' => $mainAddress->district_id]);
        $district->json();
        $district = json_decode($district, true);
        $cityName = $city['rajaongkir']['results']['city_name'];
        $districtName = $district['rajaongkir']['results']['subdistrict_name'];
        return $cityName. ' - ' .$districtName;
    }


    public function toArray($request)
    {
        return [
            'data' => [
                'sub_data' => parent::toArray($request),
            ],
            'dikirim_dari' => self::getAddress()
        ];
    }
}
