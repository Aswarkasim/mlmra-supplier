<?php

namespace App\Http\Controllers\API\Customer;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResellerResource;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AddressCustomer extends Controller
{
    public function index() {
        $auth = Auth::guard('customer-api')->user();
        return ResellerResource::make(Auth::guard('customer-api')->user());
    }

    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required'
        ]);
        if($validator->fails()){
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }


        $user = auth('customer-api')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => "error",
                'message' => "Password yang anda masukkan tidak terdaftar!"
            ], 401);
        }

        if (!$user) {
            return response()->json([
                'status' => "error",
                'message' => "User tidak terdaftar!"
            ], 401);
        }
        $customer = Customer::whereId($user['id'])->first();
        $customer->password = bcrypt($request->get('password'));
        $customer->save();
        Auth::guard('customer-api')->logout();
        return response()->json([
            'status' => "success",
            'message' => "Password berhasil diubah!"
        ], 200);
    }


    public function address() {
        $customerId = auth('customer-api')->id();
        $mainAddress = Address::whereCustomerId($customerId)->get();
        return $mainAddress;
    }

    public function detail(Request $request) {
        $address = Address::whereId($request->id)->whereCustomerId(auth('customer-api')->id())->first();
        return response()->json($address);
    }

    public function insertAddress(Request $request) {
        $validator = Validator::make($request->all(), [
            'address_name' => 'required',
            'phone_number' => 'required',
            'province_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'street' => 'required'
        ]);
        if($validator->fails()){
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }
        $mainAddress = false;
        $address = Address::whereCustomerId(auth('customer-api')->id())->first();
        if ($address) {
            $mainAddress = true;
        }

        $address = new Address();
        $address->address_name = $request->address_name;
        $address->phone_number = $request->phone_number;
        $address->customer_id = auth('customer-api')->id();
        $address->province_id = $request->province_id;
        $address->city_id = $request->city_id;
        $address->district_id = $request->district_id;
        $address->street = $request->street;
        $address->status = $mainAddress ? StatusType::INACTIVE : StatusType::ACTIVE;
        if ($address->save()) {
            return response()->json([
                'status' => "success",
                'message' => "Berhasil ditambahkan!"
            ], 201);
        }
    }

    public function editAddress(Request $request) {
        $address = Address::whereId($request->id)->whereCustomerId(auth('customer-api')->id())->first();
        $validator = Validator::make($request->all(), [
            'address_name' => 'required',
            'phone_number' => 'required',
            'province_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'street' => 'required'
        ]);
        if($validator->fails()){
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }

        $address->address_name = $request->address_name;
        $address->phone_number = $request->phone_number;
        $address->customer_id = auth('customer-api')->id();
        $address->province_id = $request->province_id;
        $address->city_id = $request->city_id;
        $address->district_id = $request->district_id;
        $address->street = $request->street;
        $address->status = $address->status == StatusType::ACTIVE ? StatusType::ACTIVE : StatusType::INACTIVE;
        if ($address->save()) {
            return response()->json([
                'status' => "success",
                'message' => "Berhasil diupdate!"
            ], 201);
        }
    }

    public function changeStatus(Request $request) {
        $currentAddress = Address::whereId($request->id)->whereCustomerId(auth('customer-api')->id())->first();
        $checkMainAddress = Address::whereCustomerId(auth('customer-api')->id())->where('id', '!=', $request->id)->get();
        $isAnyActive = false;
        $addressIdActive = null;
        $message = null;
        $status = "success";
        foreach ($checkMainAddress as $address) {
            if ($address->status == StatusType::ACTIVE) {
                $addressIdActive = $address->id;
                $isAnyActive = true;
            }
        }
        if ($currentAddress->status == StatusType::ACTIVE) {
            $message = "Maaf, status ini sudah dalam kondisi active";
            $status = "failed";
        } else if ($currentAddress->status == StatusType::INACTIVE && $isAnyActive){
            $addressWillDelete = Address::whereId($addressIdActive)->whereCustomerId(auth('customer-api')->id())->first();
            $address->delete();
            $currentAddress->status = StatusType::ACTIVE;
            $currentAddress->save();
            $message = "Status berhasil diubah menjadi alamat utama";
        } else {
            $currentAddress->status = StatusType::ACTIVE;
            $currentAddress->save();
            $message = "Status berhasil diubah menjadi alamat utama";
        }


        return response()->json([
            'status' => $status,
            'message' => $message
        ], 200);
    }
}
