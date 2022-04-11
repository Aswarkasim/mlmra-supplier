<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\ResellerLevelType;
use App\Enums\SettingType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\OtpHistory;
use App\Models\Referal;
use App\Models\ResellerShop;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Media;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Address;
use App\Models\ProductVarian;
use App\Models\Comment;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reseller;
use App\Traits\FirebaseStorage;
use App\Traits\Permissions;
use App\Traits\StringValidator;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use App\Traits\ValidationError;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Resources\ResellerResource;
use App\Http\Resources\ProductResource;


class ResellerAuthController extends Controller
{
    private static $JWT_TTL = 60;

//    public function __construct()
//    {
//        $this->middleware('auth:reseller-api', ['except' => ['login', 'register']]);
//    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'password' => 'required|string'
        ]);
        if($validator->fails()){
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }
        $credentials = request(['phone_number', 'password']);
        if (!$token = Auth::guard('reseller-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function checkValidation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'phone_number' => 'required|unique:resellers|string',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required'
        ]);

        if($validator->fails()){
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }
        if ($request->has('referal_code')) {
            $validator = Validator::make($request->all(), [
                'referal_code' => 'required'
            ]);
            if($validator->fails()){
                $val = ['validation_error' => $validator->errors()];
                return response()->json($val, 400);
            }
            $referal = Reseller::whereReferalCode($request->referal_code)->first();
            if (!$referal) {
                return response()->json([
                    'error' => 'Kode Referal tidak ditemukan'
                ], 404);
            }
        }

        $phoneNumber = $this->replacePhoneNumber($request->phone_number);

        $otpCode = rand(0000, 9999);
        $responses = Http::withHeaders([
            'API-Key' => '1d359a2a419d92b599ea18bd93502b42f6ca82b6ee5d95489d8b2aa35a7f9eae',
            'Content-Type' => 'application/json'
        ])->post('https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp', [
            'phone' => $phoneNumber,
            'messageType' => 'otp',
            'body'=> "berikut adalah kode otp anda ". $otpCode
        ]);

        $otp = new OtpHistory();
        $otp->otp = $otpCode;
        $otp->phone_number = $phoneNumber;
        $otp->otp_expired = Carbon::now()->addMinutes(30);
        $otp->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Silahkan cek kode OTP anda dan masukkan pada halaman register!'
        ], 201);
    }

    public function account()
    {
        return ResellerResource::make(Auth::guard('reseller-api')->user());
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'phone_number' => 'required|unique:resellers|string',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required'
        ]);

        if($validator->fails()){
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }
        if ($request->has('referal_code')) {
            $validator = Validator::make($request->all(), [
                'referal_code' => 'required'
            ]);
            if($validator->fails()){
                $val = ['validation_error' => $validator->errors()];
                return response()->json($val, 400);
            }
            $referal = Reseller::whereReferalCode($request->referal_code)->first();
            if (!$referal) {
                return response()->json([
                    'error' => 'Kode Referal tidak ditemukan'
                ], 404);
            }
        }
        $phoneNumber = $this->replacePhoneNumber($request->phone_number);

        $otpCheck = OtpHistory::whereOtp($request->otp)->wherePhoneNumber($phoneNumber)->where('otp_expired', '>=', Carbon::now())->first();
        if (!$otpCheck) {
            return response()->json([
                'status' => 'failed',
                'error' => 'Kode OTP tidak ditemukan'
            ], 404);
        }

        $randomCode = substr(str_shuffle(str_repeat("23456789abcdefghjkmnpqrstuvwxyz", 3)), 2, 4);
        $user = Reseller::create([
            'full_name' => $request->get('full_name'),
            'phone_number' => $phoneNumber,
            'status' => StatusType::INACTIVE,
            'level' => ResellerLevelType::PEMULA,
            'code' => $randomCode,
            'referal_code' => substr(str_shuffle(str_repeat("23456789abcdefghjkmnpqrstuvwxyz", 3)), 2, 4),
            'password' => bcrypt($request->get('password'))
        ]);
        $reseller = Reseller::whereCode($randomCode)->first();

        $resellerShop = new ResellerShop();
        $resellerShop->shop_name = $request->get('full_name');
        $resellerShop->url = Str::slug($randomCode);
        $resellerShop->reseller_id = $reseller->id;
        $resellerShop->save();

        if ($request->has('referal_code')) {
            $setting = Setting::whereSettingType(SettingType::REFERAL_PAID)->first();

            $referal = new Referal();
            $referal->referal_code = $request->referal_code;
            $referal->reseller_id = $reseller->id;
            $referal->saveOrFail();

            $resellerReferalCountAndPaid = Reseller::whereReferalCode($request->referal_code)->first();
            $resellerReferalCountAndPaid->referal_count += 1;
            $resellerReferalCountAndPaid->referal_bonus += $setting->setting_value;
            $resellerReferalCountAndPaid->saveOrFail();
        }
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 201);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('reseller-api')->refresh());
    }


    public function logout()
    {
        Auth::guard('reseller-api')->logout();
        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully logged out'
        ], 200);

    }

    public function product() {
        $product = Product::whereStatus(StatusType::PUBLISHED)->get();
        return ProductResource::collection($product);
    }

    public function detail(Request $request) {
        $productDetail = Product::whereSlug($request->slug)->first();
        $media = Media::whereCode($productDetail->media_code)->get();
        $category = Category::whereId($productDetail->category_id)->get();
        $sub_category = SubCategory::whereId($productDetail->sub_category_id)->get();
        $user = User::whereId($productDetail->user_id)->get();
        $supplierAdress = Address::whereUserId($productDetail->user_id)->whereStatus(StatusType::ACTIVE)->first();
        $varian_warna = ProductVarian::whereProductId($productDetail->id)->select('id','color','color_total')->get();
        $varian_berat = ProductVarian::whereProductId($productDetail->id)->select('id','weight','weight_total')->get();
        $varian_ukuran = ProductVarian::whereProductId($productDetail->id)->select('id','size','size_total')->get();
        $varian_tipe = ProductVarian::whereProductId($productDetail->id)->select('id','type','type_total')->get();
        $varian_rasa = ProductVarian::whereProductId($productDetail->id)->select('id','taste','taste_total')->get();
        $anotherProduct = Product::whereUserId($productDetail->user_id)
            ->where('slug', '!=', $productDetail->slug)
            ->whereStatus(StatusType::PUBLISHED)->get();
        if ($request->has('rating')) {
            $comment = Comment::with('reseller', 'reseller.media')->whereProductId($productDetail->id)->whereRating($request->rating)->get();
        } else {
            $comment = Comment::with('reseller', 'reseller.media')->whereProductId($productDetail->id)->get();
        }
        $ratings = $comment;
        $totalRating = $ratings->count();
        if ($totalRating > 0) {
            $ratingAvg = 0;
            foreach ($ratings as $rate) {
                $ratingAvg += $rate->rating;
            }
            $ratingAvg /= $totalRating;
            $ratingAvg = number_format($ratingAvg, 1, '.', '');
        } else {
            $ratingAvg = [];
        }
        $totalComment = count($comment);
        return response()->json([
            'data' => $productDetail,
            'media' => $media,
            ['varian_warna' => $varian_warna],
            ['varian_berat' => $varian_berat],
            ['varian_ukuran'=> $varian_ukuran],
            ['varian_tipe' => $varian_tipe],
            ['varian_rasa' => $varian_rasa],
            'another' => $anotherProduct,
            'category' => $category,
            'sub_category' => $sub_category,
            'supplier' => $user,
            'comment' => $comment,
            'rating_average' => $ratingAvg,
            'alamat_supplier' => $supplierAdress
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => self::$JWT_TTL
        ], 200);
    }

    public function sendOtp(Request $request)
    {
        $phoneNumber = $this->replacePhoneNumber($request->phone_number);
        $otpCode = rand(0000, 9999);
        $responses = Http::withHeaders([
            'API-Key' => '1d359a2a419d92b599ea18bd93502b42f6ca82b6ee5d95489d8b2aa35a7f9eae',
            'Content-Type' => 'application/json'
        ])->post('https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp', [
            'phone' => $phoneNumber,
            'messageType' => 'otp',
            'body'=> "berikut adalah kode otp anda ". $otpCode
        ]);

        $otp = new OtpHistory();
        $otp->otp = $otpCode;
        $otp->phone_number = $phoneNumber;
        $otp->otp_expired = Carbon::now()->addMinutes(30);
        $otp->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Silahkan cek kode OTP anda!'
        ], 201);
    }

    public function replacePhoneNumber($phoneNumber) {
        if ($phoneNumber[0] == 0) {
            $phoneNumber = str_replace("08", "628", $phoneNumber);
        } else if ($phoneNumber[0] == "+") {
            $phoneNumber = str_replace("+62", "62", $phoneNumber);
        }
        return $phoneNumber;
    }
}

