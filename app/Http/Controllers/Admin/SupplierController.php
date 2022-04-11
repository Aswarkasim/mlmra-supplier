<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Media;
use Ramsey\Uuid\Uuid;
use App\Enums\MediaType;
use App\Models\Supplier;
use App\Enums\StatusType;
use App\Enums\CategoryType;
use Illuminate\Http\Request;
use App\Exports\SupplierExport;
use App\Traits\AdminGeneralTrait;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    use AdminGeneralTrait;

    public function index(Request $request)
    {
        $search = $request->get('q');
        $listSupplier = User::where('full_name', 'like', "%" . $search . "%")
            ->orWhere('username', 'like', "%" . $search . "%")
            ->orWhere('email', 'like', "%" . $search . "%")->orderByDesc('id')->paginate(15);
        return view('admin.supplier.index', compact('listSupplier'));
    }

    //    public function create() {
    //        return view('admin.supplier.create');
    //    }
    //
    //    public function save(Request $request) {
    //
    //    }

    public function edit($id)
    {
        $supplier = User::with('media')->findOrFail($id);
        // dd($supplier);
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = User::findOrFail($id);
        $this->validate($request, [
            'username' => 'required|unique:users,username,' . $id,
            'full_name' => 'required',
            'phone_number' => 'required|unique:users,phone_number,' . $id,
            'email' => 'required|unique:users,email,' . $id,
            'whatsapp' => 'required|unique:users,whatsapp,' . $id,
        ]);
        $media = $supplier->media;
        // if ($request->hasFile('image')) {
        //     // dd($request->file('image'));
        //     $media = $this->uploadMedia($request->image, MediaType::IMAGE, CategoryType::SUPPLIER, null);
        //     // $media = $this->uploadMedia($request->file('image'), MediaType::IMAGE, CategoryType::SUPPLIER, null);
        // }

        if ($request->hasFile('image')) {

            // if ($media->image != null) {
            //     unlink('uploads/images/' . $media->image);
            // }

            $media = $request->file('image');
            // dd($media);
            $uuid = Uuid::uuid4()->toString();
            $uuid2 = Uuid::uuid4()->toString();
            $fileType = $media->getClientOriginalExtension();
            $fileSize = $media->getSize();

            // $file_name = time() . "_" . $media->getClientOriginalName();
            $file_name = $uuid . '-' . $uuid2 . '.' . $fileType;
            $storage = 'uploads/images/';
            $media->move($storage, $file_name);

            $media = Media::create([
                'file_name' => $file_name,
                'media_type' => MediaType::IMAGE,
                'file_size' => $fileSize,
                'code'      => null,
                'category_type' => CategoryType::SUPPLIER
            ]);
        }

        $supplier->media_id = $media ? $media->id : null;
        $supplier->username = $request->username;
        $supplier->full_name = $request->full_name;
        $supplier->phone_number = $request->phone_number;
        $supplier->email = $request->email;
        $supplier->whatsapp = $request->whatsapp;
        $supplier->status = $request->status;
        $supplier->save();
        Toastr::success('Berhasil diupdate:)', 'Success');
        return redirect(route('admin.supplier'))->with(['success' => 'Category Diperbaharui!']);
    }

    public function exports()
    {
        return Excel::download(new SupplierExport(), 'supplier.xlsx');
    }
}

//coba kan
