<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\RoleType;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller
{
    public static function getResellerId() {
        return Auth::guard('reseller-api')->id();
    }

    public function list() {
        $bankAccount = BankAccount::with('reseller')->whereResellerId(self::getResellerId())->first();
        return $bankAccount;
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string',
            'account_number' => 'required|unique:bank_accounts',
            'bank_name' => 'required|string'
        ]);

        if($validator->fails()){
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }

        $account = new BankAccount();
        $account->user_type = RoleType::RESELLER;
        $account->account_name = $request->account_name;
        $account->account_number = $request->account_number;
        $account->bank_name = $request->bank_name;
        $account->reseller_id = self::getResellerId();
        $account->save();
        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Rekening berhasil ditambahkan!'
            ],
        ], 201);
    }

    public function edit(Request $request) {
//        $validator = Validator::make($request->all(), [
//            'account_name' => 'required|string',
//            'account_number' => 'required|unique:bank_accounts,id,'.$request->id,
//            'bank_name' => 'required|string'
//        ]);
//
//        if($validator->fails()){
//            $val = ['validation_error' => $validator->errors()];
//            return response()->json($val, 400);
//        }
//
//        $account = BankAccount::whereResellerId(self::getResellerId())->first();
//        $account->user_type = RoleType::RESELLER;
//        $account->account_name = $request->account_name;
//        $account->account_number = $request->account_number;
//        $account->bank_name = $request->bank_name;
//        $account->reseller_id = self::getResellerId();
//        $account->save();
        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Rekening berhasil dihapus!'
            ],
        ], 201);
    }
}
