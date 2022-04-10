<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Enums\MediaType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Reseller;
use App\Export\ResellerExport;
use App\Traits\AdminGeneralTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ResellerController extends Controller
{
    use AdminGeneralTrait;

    public function index(Request $request) {
        $search = $request->get('q');
        $listReseller = Reseller::where('full_name', 'like', "%".$search."%")
                ->orWhere('username', 'like', "%".$search."%")
                ->orWhere('email', 'like', "%".$search."%")->orderByDesc('id')->paginate(15);
        return view('admin.reseller.index', compact('listReseller'));
    }

//    public function create() {
//        return view('admin.reseller.create');
//    }
//
//    public function save(Request $request) {
//
//    }

    public function edit($id) {
        $reseller = Reseller::findOrFail($id);
        return view('admin.reseller.edit', compact('reseller'));
    }

    public function update(Request $request, $id) {
        $reseller = Reseller::findOrFail($id);
        $this->validate($request, [
            'username' => 'required|unique:resellers,username,'.$id,
            'full_name' => 'required',
            'phone_number' => 'required|unique:resellers,phone_number,'.$id,
            'email' => 'required|unique:resellers,email,'.$id,
            'whatsapp' => 'required|unique:resellers,whatsapp,'.$id,
        ]);
        $media = $reseller->media;
        if ($request->hasFile('image')) {
            $media = $this->uploadMedia($request->image, MediaType::IMAGE, CategoryType::RESELLER, null);
        }
        $reseller->media_id = $media ? $media->id : null;
        $reseller->username = $request->username;
        $reseller->full_name = $request->full_name;
        $reseller->phone_number = $request->phone_number;
        $reseller->email = $request->email;
        $reseller->whatsapp = $request->whatsapp;
        $reseller->status = $request->status;
        $reseller->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.reseller'))->with(['success' => 'Category Diperbaharui!']);
    }

    public function exports()
    {
        return Excel::download(new ResellerExport(), 'reseller.xlsx');
    }
}
