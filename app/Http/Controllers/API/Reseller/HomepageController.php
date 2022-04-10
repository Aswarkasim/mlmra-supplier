<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\CategoryType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\ProductNewResource;
use App\Http\Resources\ProductPopularResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductReseller;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use App\Http\Resources\CategoryResource;

class HomepageController extends Controller
{
    public function allCategoryProduct()
    {
        $productCategory = Category::with('media')->whereCategoryType(CategoryType::PRODUCT)
            ->whereStatus(StatusType::PUBLISHED)->get();
        return CategoryResource::collection($productCategory);
    }

    public function newProduct() {
        $dateAgo = date("Y-m-d H:i:s", strtotime("-15 day"));
        $newProduct = Product::where('updated_at', '>=', $dateAgo)->get();
        return ProductNewResource::collection($newProduct);
    }

    public function productPopular() {
        $popularProduct = ProductFeature::where('total_comment', '>=', 10)
            ->orWhere('total_transaction', '>=', 10)->orderByDesc('total_transaction')->get();
        return ProductPopularResource::collection($popularProduct);
    }

    public function product() {
        $product = Product::whereStatus(StatusType::PUBLISHED)->get();
        return ProductResource::collection($product);
    }

    public function shareProduct(Request $request) {
//        $product = new ProductReseller();
//        $product->reseller_id = Auth::guard('reseller-api')->id();
//        $product->product_id = $request->product_id;
//        if ($request->product_id && $product->save()) {
//            return response()->json([
//                'data' => [
//                    'link' => 'DISINI AKAN ADA LINK YANG BISA DIBAGIKAN KE WA'
//                ],
//            ], 201);
//        } else {
//            return "Error";
//        }
    }

    public function allBrand() {
        $brands = User::with('media')->whereStatus(StatusType::ACTIVE)->select("username", "media_id")->get();
        return BrandResource::collection($brands);
    }
}
