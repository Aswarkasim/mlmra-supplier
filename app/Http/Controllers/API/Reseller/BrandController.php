<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\ProductFeature;
use App\Models\User;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function filterBrandByAbjad(Request $request) {
        $brands = User::with('media')->whereStatus(StatusType::ACTIVE)->get();
        $brands = $brands->sortBy([
            ['username', $request->type]
        ]);
        return BrandResource::collection($brands);
    }

    public function search(Request $request) {
        $brands = User::with('media')->whereStatus(StatusType::ACTIVE)->where('username', 'LIKE', '%'.$request->name.'%')->get();
        return BrandResource::collection($brands);
    }

    public function populer() {
        $brandsPopulerId = ProductFeature::where('total_brand_make_populer', '>=', 10)->get();
        $id = [];
        for ($i = 0; $i < count($brandsPopulerId); $i++) {
            array_push($id, $brandsPopulerId[$i]->user_id);
        }
        $user = User::with('media')->whereIn('id', $id)->get();
        return response()->json($user);
    }
}
