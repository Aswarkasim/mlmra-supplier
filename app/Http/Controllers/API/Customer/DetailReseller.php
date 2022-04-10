<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\CategoryResellerLog;
use App\Models\ProductReseller;
use App\Models\Reseller;
use App\Models\ResellerShop;
use Illuminate\Http\Request;

class DetailReseller extends Controller
{
    public function detail(Request $request)
    {
        $resellerShop = ResellerShop::whereResellerId($request->id)->first();
        if (!$resellerShop) {
            return response()->json([
                'status' => 'failed',
                'data' => 'Whoopss, Tidak ditemukan'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $resellerShop
            ], 200);
        }
    }

    public function category(Request $request)
    {
        $category = CategoryResellerLog::with('category','category.media')->whereResellerId($request->id)->get();
        return $category;
    }

    public function chatReseller(Request $request)
    {
        $phone_number = Reseller::whereId($request->id)->first();
        $url = "https://api.whatsapp.com/send?phone=.$phone_number->phone_number.&text=Saya ingin bertanya mengenai produk anda";
        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Silahkan klik link!',
                'link' => $url
            ],
        ], 200);
    }

    public function product(Request $request)
    {
        $product = ProductReseller::with('product')->whereResellerId($request->id)->get();
        return $product;
    }
}
