<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Enums\MediaType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Traits\AdminGeneralTrait;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    use AdminGeneralTrait;

    public function index(Request $request) {
        $search = $request->get('q');
        $listCustomer = Customer::where('full_name', 'like', "%".$search."%")
            ->orWhere('username', 'like', "%".$search."%")
            ->orWhere('email', 'like', "%".$search."%")->orderByDesc('id')->paginate(15);
        return view('admin.customer.index', compact('listCustomer'));
    }

    public function edit($id) {
        $statuses = [StatusType::INACTIVE, StatusType::ACTIVE];
        $customer = Customer::findOrFail($id);
        return view('admin.customer.edit', compact('customer', 'statuses'));
    }

    public function update(Request $request, $id) {
        $customer = Customer::findOrFail($id);
        $this->validate($request, [
            'username' => 'required|unique:customers,username,'.$id,
            'full_name' => 'required',
            'phone_number' => 'required|unique:customers,phone_number,'.$id,
            'email' => 'required|unique:customers,email,'.$id,
            'whatsapp' => 'required|unique:customers,whatsapp,'.$id,
        ]);
        $media = $customer->media;
        if ($request->hasFile('image')) {
            $media = $this->uploadMedia($request->image, MediaType::IMAGE, CategoryType::CUSTOMER, null);
        }
        $customer->media_id = $media ? $media->id : null;
        $customer->username = $request->username;
        $customer->full_name = $request->full_name;
        $customer->phone_number = $request->phone_number;
        $customer->email = $request->email;
        $customer->whatsapp = $request->whatsapp;
        $customer->status = $request->status;
        $customer->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.customer'))->with(['success' => 'Category Diperbaharui!']);
    }
}
