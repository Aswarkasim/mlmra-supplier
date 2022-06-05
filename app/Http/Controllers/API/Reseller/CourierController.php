<?php

namespace App\Http\Controllers\API\Reseller;

use App\Models\Courier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourierController extends Controller
{
    //
    function index()
    {
        $now = now();
        // die($now);
        $limit = request('limit');
        if ($limit) {
            $courier = Courier::limit($limit)->get();
        } else {
            $courier = Courier::get();
        }
        return response()->json([
            'courier'     => $courier
        ], 201);
    }
}
