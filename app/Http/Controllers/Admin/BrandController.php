<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActionType;
use App\Enums\CategoryType;
use App\Enums\MediaType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Media;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index() {
        $brands = Brand::whereStatuss(StatusType::PUBLISHED)->get();
        return view('admin.brand.index', compact('brands'));
    }

    public function create() {
        return view('admin.brand.create');
    }

    public function save(Request $request) {
        $this->validate($request, [
            'brand_name' => 'required',
            'thumbnail' => 'image'
        ]);
        $media = new Media();
        $brand = new Brand();
        if ($request->hasFile('thumbnail')) {
            $media = $this->uploadMedia($request->thumbnail, MediaType::IMAGE, CategoryType::BRAND, null);
        }
        $brand->name = $request->brand_name;
        $brand->status = StatusType::DRAFT;
        $brand->media_id = $media->id;
        $brand->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan:)','Success');
        return redirect(route('admin.brand'));
    }

    public function edit($id) {
        $brand = Brand::findOrFail($id)->first();
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, $id) {
        $brand = Brand::findOrFail($id);
        $this->validate($request, [
            'brand_name' => 'required',
            'thumbnail' => 'image'
        ]);
        $media = $brand->media;
        if ($request->hasFile('thumbnail')) {
            $media = $this->uploadMedia($request->thumbnail, MediaType::IMAGE, CategoryType::BRAND, null);
        }
        $brand->media_id = $media ? $media->id : null;
        $brand->name = $request->title;

        $actionType = ActionType::getInstance($request->action_type);

        if ($actionType->is(ActionType::PUBLISH)) {
            $brand->status = StatusType::PUBLISHED;
            $brand->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.brand'))->with(['success' => 'Brand Diperbaharui!']);
        } else if ($actionType->is(ActionType::ARCHIVE)) {
            $brand->status = StatusType::ARCHIVED;
            $brand->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.brand'))->with(['success' => 'Brand Diperbaharui!']);
        }

        $brand->status = StatusType::DRAFT;
        $brand->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.brand.edit', $brand->id))->with(['success' => 'Brand Diperbaharui!']);
    }
}
