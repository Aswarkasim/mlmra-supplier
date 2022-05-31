<?php

namespace App\Http\Controllers\API\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Flashsale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlashsaleController extends Controller
{
    //
    function index()
    {
        $now = now();
        // die($now);
        $limit = request('limit');
        if ($limit) {
            $flashsale = Flashsale::with('product')->whereIsActive(true)->limit($limit)->get();
        } else {
            $flashsale = Flashsale::with('product')->whereIsActive(true)->get();
        }
        return response()->json([
            'flashsale'     => $flashsale
        ], 201);
    }

    function create(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'transaction_id' => 'required',
        //     'image' => 'required'
        // ]);
        // if ($validator->fails()) {
        //     $val = ['validation_error' => $validator->errors()];
        //     return response()->json($val, 400);
        // }

        // die('masuk');

        $data = [
            'product_id'    => $request->product_id,
            'discount'      => $request->discount,
            'time_start'      => $request->time_start,
            'time_end'      => $request->time_end,
        ];

        $product = Product::find($data['product_id']);
        $discount = $request->discount;
        $data['price_discount'] = $product->reseller_price * $discount / 100;
        $flashsale = Flashsale::create($data);
        return response()->json([
            'status' => 'success',
            'flashsale'     => $flashsale
        ], 201);
    }
}
