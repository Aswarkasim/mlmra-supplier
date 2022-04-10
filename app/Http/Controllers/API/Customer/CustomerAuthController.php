<?php

namespace App\Http\Controllers\API\Customer;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    private static $JWT_TTL = 60;


    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required'
        ]);

        if($validator->fails()){
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }

        $isAvailablePhone = Customer::wherePhoneNumber($request->phone_number)->first();
        if (!$isAvailablePhone) {
            $user = Customer::create([
                'phone_number' => $request->get('phone_number'),
                'status' => StatusType::INACTIVE,
                'password' => bcrypt($request->get('password'))
            ]);
        }

        $credentials = request(['phone_number', 'password']);
        if (!$token = Auth::guard('customer-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        Auth::guard('customer-api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ], 200);

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => self::$JWT_TTL
        ], 200);
    }
}
