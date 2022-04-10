<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index(Request $request) {
        $provinces = Province::pluck('name', 'id');
        $search = $request->get('q');
        $address = Address::whereUserId(Auth::user()->id)->where('address_name', 'like', "%".$search."%")->orderByDesc('id')->paginate(15);
        return view('admin.address.index', compact('address', 'provinces'));
    }

    public function indexAdmin(Request $request) {
        $provinces = Province::pluck('name', 'id');
        $search = $request->get('q');
        $address = Address::where('address_name', 'like', "%".$search."%")->orderByDesc('id')->paginate(15);
        return view('admin.address.index', compact('address','provinces'));
    }

    public function save(Request $request) {
        $this->validate($request, [
            'address_name' => 'required',
            'province_origin' => 'required|min:1',
            'city_origin' => 'required|min:1',
            'district_origin' => 'required|min:1',
            'street' => 'required'
        ]);
        $address = new Address();
        $address->address_name = $request->address_name;
        $address->phone_number = Auth::user()->phone_number;
        $address->user_id = Auth::user()->id;
        $address->province_id = $request->province_origin;
        $address->city_id = $request->city_origin;
        $address->district_id = $request->district_origin;
        $address->status = StatusType::INACTIVE;
        $address->street = $request->street;
        $address->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambah:)','Success');
        return redirect(route('admin.address'));
    }

    public function updateStatus($id) {
        $address = Address::findOrFail($id);
        $anotherAddress = Address::where('id', '!=', $id)->get();
        if ($address->status == StatusType::INACTIVE) {
            foreach ($anotherAddress as $adress) {
                $adress->status = StatusType::INACTIVE;
                $adress->save();
            }
            $address->status = StatusType::ACTIVE;
        } else {
            $activeAddress = false;
            foreach ($anotherAddress as $address) {
                if ($address->status == StatusType::ACTIVE) {
                    $activeAddress = true;
                }
            }
            if ($activeAddress) {
                $address->status = StatusType::INACTIVE;
            } else {
                \Brian2694\Toastr\Facades\Toastr::success('Harus ada alamat utama yang active:)','Gagal');
                return redirect(route('admin.address'));
            }
        }
        $address->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.address'));
    }

    public function delete($id) {
        $address = Address::findOrFail($id);
        if ($address->status == StatusType::ACTIVE) {
            \Brian2694\Toastr\Facades\Toastr::success('Alamat active tidak bisa dihapus:)','Gagal');
        } else {
            if ($address->delete()) {
                \Brian2694\Toastr\Facades\Toastr::success('Berhasil dihapus:)','Success');
            }
        }
        return redirect(route('admin.address'));
    }
}
