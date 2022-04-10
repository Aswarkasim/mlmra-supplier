<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function category() {
        $category = Category::whereStatus(StatusType::PUBLISHED)->get();
        return CategoryResource::collection($category);
    }

    public function subcategory() {
        $subCategory = SubCategory::with('category')->whereStatus(StatusType::PUBLISHED)->get();
        return SubCategoryResource::collection($subCategory);
    }

    public function searchCategory(Request $request) {
        $category = Category::whereStatus(StatusType::PUBLISHED)->where('name', 'LIKE', '%'.$request->name.'%')->get();
        return CategoryResource::collection($category);
    }

    public function filterCategory(Request $request) {
        $category = Category::whereStatus(StatusType::PUBLISHED)->get();
        $category = $category->sortBy([
            ['name', $request->type]
        ]);
        return CategoryResource::collection($category);
    }
}
