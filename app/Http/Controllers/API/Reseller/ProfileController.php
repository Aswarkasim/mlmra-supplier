<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\StatusType;
use App\Http\Controllers\API\JsonFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\PointResource;
use App\Http\Resources\ReferalResource;
use App\Http\Resources\ResellerResource;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\PointHistory;
use App\Models\Referal;
use App\Models\Reseller;
use App\Models\ResellerCoupon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\New_;

class ProfileController extends Controller
{
    public function index() {
        return ResellerResource::make(Auth::guard('reseller-api')->user());
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

        $user = auth('reseller-api')->user();
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
        $reseller = Reseller::whereId($user['id'])->first();
        $reseller->password = bcrypt($request->get('password'));
        $reseller->save();
        Auth::guard('reseller-api')->logout();
        return response()->json([
            'status' => "success",
            'message' => "Password berhasil diubah!"
        ], 200);
    }

    public function referal() {
        $user = auth('reseller-api')->user();
        $referals = Referal::whereReferalCode($user['referal_code'])->get();
        return ReferalResource::collection($referals);
    }

    public function point() {
        $user = auth('reseller-api')->user();
        $point_history = PointHistory::whereResellerId($user->id)->get();
        return PointResource::collection($point_history);
    }

    public function swapCoupon(Request $request) {
        $coupon = Coupon::whereId($request->coupon_id)->first();
        $reseller = auth('reseller-api')->user();
        $resellerCouponCheck = ResellerCoupon::whereCouponId($coupon->id)->first();
        if ($resellerCouponCheck) {
            $resellerCouponCheck->time_applied += 1;
            $resellerCouponCheck->save();
            $reseller->point -= $coupon->min_point;
            $reseller->save();
            return response()->json([
                'status' => 'success',
                'message' => 'sukses ditukar'
            ], 200);
        } else if ($reseller->point >= $coupon->min_point) {
            $resellerCoupon = new ResellerCoupon();
            $resellerCoupon->time_applied = 1;
            $resellerCoupon->coupon_id = $coupon->id;
            $resellerCoupon->reseller_id = $reseller->id;
            $resellerCoupon->save();
            $reseller->point -= $coupon->min_point;
            $reseller->save();
            return response()->json([
                'status' => 'success',
                'message' => 'sukses ditukar'
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'maaf, point anda tidak cukup'
            ], 400);
        }
    }

    public function address() {
        $resellerId = auth('reseller-api')->id();
        $mainAddress = Address::whereResellerId($resellerId)->get();
        return $mainAddress;
    }

    public function detail(Request $request) {
        $address = Address::whereId($request->id)->whereResellerId(auth('reseller-api')->id())->first();
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
        $address = Address::whereResellerId(auth('reseller-api')->id())->first();
        if ($address) {
            $mainAddress = true;
        }

        $address = new Address();
        $address->address_name = $request->address_name;
        $address->phone_number = $request->phone_number;
        $address->reseller_id = auth('reseller-api')->id();
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
        $address = Address::whereId($request->id)->whereResellerId(auth('reseller-api')->id())->first();
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
        $address->reseller_id = auth('reseller-api')->id();
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
        $currentAddress = Address::whereId($request->id)->whereResellerId(auth('reseller-api')->id())->first();
        $checkMainAddress = Address::whereResellerId(auth('reseller-api')->id())->where('id', '!=', $request->id)->get();
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
            $addressWillDelete = Address::whereId($addressIdActive)->whereResellerId(auth('reseller-api')->id())->first();
            $address->delete();
            $message = "Status berhasil diubah menjadi alamat utama";
        } else {
            $message = "Status berhasil diubah menjadi alamat utama";
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ], 200);

    }
}
