<?php

namespace App\Http\Resources;

use App\Models\Address;
use App\Models\Media;
use App\Models\ResellerCart;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CartResource extends JsonResource
{

    public static function getAddress()
    {
        $productId = ResellerCart::whereResellerId(Auth::guard('reseller-api')->id())->first();
        if (!$productId) {
            return response()->json([
                'status' => "failed",
                'message' => "Tidak ada produk dalam keranjang!"
            ], 200);
        }
        // $mainAddress = Address::whereUserId($productId->product->user->id)->first();
        // if (!$mainAddress) {
        //     return response()->json([
        //         'status' => "failed",
        //         'message' => "Harap lengkapi alamat anda!"
        //     ], 200);
        // }
        $city = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/city', ['id' => 254]);
        // ])->get('https://pro.rajaongkir.com/api/city', ['id' => '$mainAddress->city_id]');
        $city->json();
        $city = json_decode($city, true);
        $district = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/subdistrict', ['id' => 3591]);
        // ])->get('https://pro.rajaongkir.com/api/subdistrict', ['id' => $mainAddress->district_id]);
        $district->json();
        $district = json_decode($district, true);
        $cityName = $city['rajaongkir']['results']['city_name'];
        $districtName = $district['rajaongkir']['results']['subdistrict_name'];
        return $cityName . ' - ' . $districtName;
    }


    public function toArray($request)
    {
        return [
            'data' => [
                'sub_data' => parent::toArray($request),
                // 'supplier' => $this->user ? $this->user->username : null,
                // 'category' => $this->category ? $this->category->name : null,
                // 'sub_category' => $this->subcategory ? $this->subcategory->name : null,
                // 'media' => Media::whereCode($this->media_code)->get(),
                // 'media' => Media::whereCode($this->media_code)->first(),
            ],
            // 'dikirim_dari' => self::getAddress()

        ];
    }
}
