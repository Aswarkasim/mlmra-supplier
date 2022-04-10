<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActionType;
use App\Enums\CategoryType;
use App\Enums\MediaType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\SubCategory;
use App\Traits\AdminGeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    use AdminGeneralTrait;

    public function index() {
        $listCategory = Category::orderByDesc('id')->paginate(15);
        return view('admin.category.index', compact('listCategory'));
    }

    public function create() {
        $listCategoryType = CategoryType::getValues();
        return view('admin.category.create', compact('listCategoryType'));
    }

    public function save(Request $request) {
        $this->validate($request, [
            'title' => 'required',
            'category' => 'required',
            'thumbnail' => 'image'
        ]);
        $media = new Media();
        $category = new Category();
        if ($request->hasFile('thumbnail')) {
            $media = $this->uploadMedia($request->thumbnail, MediaType::IMAGE, CategoryType::CATEGORY, null);
        }
        $category->name = $request->title;
        $category->category_type = $request->category;
        $category->status = StatusType::DRAFT;
        $category->media_id = $media->id;
        $category->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan:)','Success');
        return redirect(route('admin.category'));

    }

    public function edit($id) {
        $listCategoryType = CategoryType::getValues();
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('listCategoryType', 'category'));
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);
        $this->validate($request, [
            'title' => 'required',
            'category' => 'required',
            'thumbnail' => 'image'
        ]);
        $media = $category->media;
        if ($request->hasFile('thumbnail')) {
            $media = $this->uploadMedia($request->thumbnail, MediaType::IMAGE, CategoryType::CATEGORY, null);
        }
        $category->media_id = $media ? $media->id : null;
        $category->name = $request->title;
        $category->category_type = $request->category;

        $actionType = ActionType::getInstance($request->action_type);

        if ($actionType->is(ActionType::PUBLISH)) {
            $category->status = StatusType::PUBLISHED;
            $category->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.category'))->with(['success' => 'Category Diperbaharui!']);
        } else if ($actionType->is(ActionType::ARCHIVE)) {
            $category->status = StatusType::ARCHIVED;
            $category->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.category'))->with(['success' => 'Category Diperbaharui!']);
        }

        $category->status = StatusType::DRAFT;
        $category->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.category.edit', $category->id))->with(['success' => 'Category Diperbaharui!']);
    }

    // Sub Category
    public function subCategory() {
        $listSubCategory = SubCategory::orderByDesc('id')->paginate(15);
        return view('admin.subcategory.index', compact('listSubCategory'));
    }

    public function createSubCategory() {
        $listSubCategoryType = Category::whereCategoryType(CategoryType::PRODUCT)->whereStatus(StatusType::PUBLISHED)->get();
        return view('admin.subcategory.create', compact('listSubCategoryType'));
    }

    public function saveSubCategory(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'category' => 'required'
        ]);
        $subcategory = new SubCategory();
        $subcategory->name = $request->name;
        $subcategory->status = StatusType::DRAFT;
        $subcategory->category_id = $request->category;
        $subcategory->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan:)','Success');
        return redirect(route('admin.subcategory'));

    }

    public function editSubCategory($id) {
        $listSubCategoryType = Category::whereCategoryType(CategoryType::PRODUCT)->whereStatus(StatusType::PUBLISHED)->get();
        $subCategory = SubCategory::findOrFail($id);
        return view('admin.subcategory.edit', compact('listSubCategoryType', 'subCategory'));
    }

    public function updateSubCategory(Request $request, $id) {
        $category = SubCategory::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'category' => 'required'
        ]);
        $category->name = $request->name;
        $category->category_id = $request->category;

        $actionType = ActionType::getInstance($request->action_type);

        if ($actionType->is(ActionType::PUBLISH)) {
            $category->status = StatusType::PUBLISHED;
            $category->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.subcategory'))->with(['success' => 'SubCategory Diperbaharui!']);
        } else if ($actionType->is(ActionType::ARCHIVE)) {
            $category->status = StatusType::ARCHIVED;
            $category->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.subcategory'))->with(['success' => 'Sub Category Diperbaharui!']);
        }

        $category->status = StatusType::DRAFT;
        $category->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.subcategory.edit', $category->id))->with(['success' => 'Sub Category Diperbaharui!']);
    }

    public function categorySelect($id) {
        $subCategory = SubCategory::where('category_id', $id)->whereStatus(StatusType::PUBLISHED)->pluck('name', 'id');
        return response()->json($subCategory);
    }
}
