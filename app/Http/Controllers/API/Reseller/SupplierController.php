<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandPopulerResource;
use App\Http\Resources\ProductNewResource;
use App\Models\CategorySupplierLog;
use App\Models\Product;
use App\Models\ProductFeature;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function allProduct(Request $request) {
        $allProduct = Product::whereUserId($request->brand_id)->whereStatus(StatusType::PUBLISHED)->get();
        return ProductNewResource::collection($allProduct);
    }

    public function newProduct(Request $request) {
        $dateAgo = date("Y-m-d H:i:s", strtotime("-15 day"));
        $newProduct = Product::where('updated_at', '>=', $dateAgo)->whereUserId($request->brand_id)->get();
        return ProductNewResource::collection($newProduct);
    }

    public function populerProduct(Request $request) {
        $product = ProductFeature::with('product','product.user','product.category')->whereUserId($request->brand_id)->where('total_comment', '>=', 10)->select('product_id')->get();
        return BrandPopulerResource::collection($product);
    }

    public function category(Request $request) {
        $logCategory = CategorySupplierLog::with('category')->whereUserId($request->brand_id)->get();
        return $logCategory;
    }

    public function productBySubCategory(Request $request) {
        $allProduct = Product::whereSubCategoryId($request->sub_category_id)->whereStatus(StatusType::PUBLISHED)->get();
        return ProductNewResource::collection($allProduct);
    }

    public function searchProductBySubCategory(Request $request) {
        $allProduct = Product::whereSubCategoryId($request->sub_category_id)->where('title', 'LIKE', '%'.$request->title.'%')->whereStatus(StatusType::PUBLISHED)->get();
        return ProductNewResource::collection($allProduct);
    }
}
