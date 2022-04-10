<?php

namespace App\Http\Controllers\API\Customer;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductNewResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\CategoryResellerLog;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryReseller extends Controller
{
    public function category(Request $request) {
        $category = CategoryResellerLog::with('category','category.media', 'category.subcategory')->whereResellerId($request->id)->get();
        return $category;
    }

    public function searchCategory(Request $request) {
        $category = CategoryResellerLog::with('category', 'category.media','category.subcategory')->whereHas('category', function ($query) use($request) {
            $query->where('name', 'LIKE', '%'.$request->name.'%');
        })->whereResellerId($request->id)->get();
        return $category;
    }

    public function filterCategory(Request $request) {
        $category = CategoryResellerLog::with('category','category.media', 'category.subcategory')->whereHas('category', function ($query) use($request) {
            $query->orderBy('name', $request->type);
        })->whereResellerId($request->id)->get();
        return $category;
    }

    public function productBySubCategory(Request $request) {
        $allProduct = Product::whereSubCategoryId($request->sub_category_id)->whereStatus(StatusType::PUBLISHED)->get();
        return $allProduct;
    }

    public function searchProductBySubCategory(Request $request) {
        $allProduct = Product::whereSubCategoryId($request->sub_category_id)->where('title', 'LIKE', '%'.$request->title.'%')->whereStatus(StatusType::PUBLISHED)->get();
        return ProductNewResource::collection($allProduct);
    }
}
