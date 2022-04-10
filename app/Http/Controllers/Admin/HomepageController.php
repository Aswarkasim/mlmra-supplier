<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActionType;
use App\Enums\ContentType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Homepage;
use App\Traits\AdminGeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Media;
use App\Enums\CategoryType;
use App\Enums\MediaType;

class HomepageController extends Controller
{
    use AdminGeneralTrait;

    public function editMainContent() {
        $mainContent = Homepage::whereContentType(ContentType::MAIN_CONTENT)->first();
        return view('admin.homepage.editMainContent', compact('mainContent'));
    }

    public function updateMainContent(Request $request) {
        $this->validate($request, [
            'title' => 'required',
            'description_1' => 'nullable',
            'description_2' => 'nullable',
            'button_text' => 'nullable',
            'image' => 'nullable|image',
        ]);
        $media = new Media();
        if ($request->hasFile('image')) {
            $media = $this->uploadMedia($request->image, MediaType::IMAGE, CategoryType::MAIN_CONTENT);
        }
        $mainContent = Homepage::first();
        $mainContent->title = $request->title;
        $mainContent->description_1 = $request->description_1;
        $mainContent->description_2 = $request->description_2;
        $mainContent->button_text = $request->button_text;
        $mainContent->status = StatusType::DRAFT;
        $mainContent->media_id = $media->id;
        $mainContent->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.mainContent.edit'));
    }

    public function featured() {
        $listFeatured = Homepage::whereContentType(ContentType::FEATURED)->paginate(3);
        return view('admin.homepage.featured', compact('listFeatured'));
    }

    public function createFeatured() {
        return view('admin.homepage.createFeatured');
    }

    public function saveFeatured(Request $request) {
        $this->validate($request, [
            'title' => 'required',
            'description_1' => 'required'
        ]);
        $featured = new Homepage();
        $featured->title = $request->title;
        $featured->description_1 = $request->description_1;
        $featured->status = StatusType::DRAFT;
        $featured->content_type = ContentType::FEATURED;
        $featured->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan:)','Success');
        return redirect(route('admin.featured'));
    }

    public function editFeatured(Request $request, $id) {
        $featured = Homepage::findOrFail($id);
        return view('admin.homepage.editFeatured', compact('featured'));
    }

    public function updateFeatured(Request $request, $id) {
        $this->validate($request, [
            'title' => 'required',
            'description_1' => 'required'
        ]);
        $featured = Homepage::findOrFail($id);
        $featured->title = $request->title;
        $featured->description_1 = $request->description_1;
        $actionType = ActionType::getInstance($request->action_type);

        if ($actionType->is(ActionType::PUBLISH)) {
            $featured->status = StatusType::PUBLISHED;
            $featured->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.featured'))->with(['success' => 'Fitur Diperbaharui!']);
        } else if ($actionType->is(ActionType::ARCHIVE)) {
            $featured->status = StatusType::ARCHIVED;
            $featured->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.featured'))->with(['success' => 'Fitur Diperbaharui!']);
        }

        $featured->status = StatusType::DRAFT;
        $featured->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.featured'));
    }

    public function editFeaturedBanner() {
        $featuredBanner = Homepage::whereContentType(ContentType::FEATURED_IMAGE)->first();
        return view('admin.homepage.editFeaturedBanner', compact('featuredBanner'));
    }

    public function updateFeaturedBanner(Request $request) {
        $featuredBanner = Homepage::whereContentType(ContentType::FEATURED_IMAGE)->first();
        $this->validate($request, [
            'thumbnail' => 'image'
        ]);
        $media = $featuredBanner->media;
        if ($request->hasFile('thumbnail')) {
            $media = $this->uploadMedia($request->thumbnail, MediaType::IMAGE, CategoryType::FEATURED_IMAGE);
        }
        $featuredBanner->media_id = $media ? $media->id : null;
        $actionType = ActionType::getInstance($request->action_type);

        if ($actionType->is(ActionType::PUBLISH)) {
            $featuredBanner->status = StatusType::PUBLISHED;
            $featuredBanner->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.featured.banner.edit'))->with(['success' => 'Featured Banner Diperbaharui!']);
        } else if ($actionType->is(ActionType::ARCHIVE)) {
            $featuredBanner->status = StatusType::ARCHIVED;
            $featuredBanner->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.featured.banner.edit'))->with(['success' => 'Featured Banner Diperbaharui!']);
        }

        $featuredBanner->status = StatusType::DRAFT;
        $featuredBanner->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.featured.banner.edit'));
    }

    public function keuntungan() {
        $listKeuntungan = Homepage::whereContentType(ContentType::PROFIT)->paginate(3);
        return view('admin.homepage.keuntungan', compact('listKeuntungan'));
    }

    public function createKeuntungan() {
        return view('admin.homepage.createKeuntungan');
    }

    public function saveKeuntungan(Request $request) {
        $this->validate($request, [
            'title' => 'required',
            'description_1' => 'required',
            'image' => 'nullable|image',
        ]);
        $media = new Media();
        if ($request->hasFile('thumbnail')) {
            $media = $this->uploadMedia($request->thumbnail, MediaType::IMAGE, CategoryType::PROFIT);
        }
        $keuntungan = new Homepage();
        $keuntungan->title = $request->title;
        $keuntungan->description_1 = $request->description_1;
        $keuntungan->status = StatusType::DRAFT;
        $keuntungan->content_type = ContentType::PROFIT;
        $keuntungan->media_id = $media->id;
        $keuntungan->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan:)','Success');
        return redirect(route('admin.keuntungan'));
    }

    public function editKeuntungan(Request $request, $id) {
        $keuntungan = Homepage::findOrFail($id);
        return view('admin.homepage.editKeuntungan', compact('keuntungan'));
    }

    public function updateKeuntungan(Request $request, $id) {
        $keuntungan = Homepage::findOrFail($id);
        $this->validate($request, [
            'title' => 'required',
            'description_1' => 'required',
            'image' => 'nullable|image',
        ]);
        $media = $keuntungan->media;
        if ($request->hasFile('thumbnail')) {
            $media = $this->uploadMedia($request->thumbnail, MediaType::IMAGE, CategoryType::PROFIT);
        }
        $keuntungan->title = $request->title;
        $keuntungan->description_1 = $request->description_1;
        $keuntungan->media_id = $media ? $media->id : null;
        $actionType = ActionType::getInstance($request->action_type);

        if ($actionType->is(ActionType::PUBLISH)) {
            $keuntungan->status = StatusType::PUBLISHED;
            $keuntungan->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.keuntungan'))->with(['success' => 'Featured Banner Diperbaharui!']);
        } else if ($actionType->is(ActionType::ARCHIVE)) {
            $keuntungan->status = StatusType::ARCHIVED;
            $keuntungan->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.keuntungan'))->with(['success' => 'Featured Banner Diperbaharui!']);
        }

        $keuntungan->status = StatusType::DRAFT;
        $keuntungan->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.keuntungan'));
    }
}
