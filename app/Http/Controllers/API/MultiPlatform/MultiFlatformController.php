<?php

namespace App\Http\Controllers\API\MultiPlatform;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\ProductResource;
use App\Models\BankAccount;
use App\Models\CategoryResellerLog;
use App\Models\Coupon;
use App\Models\Media;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductReseller;
use App\Models\ResellerCoupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;

class MultiFlatformController extends Controller
{
    public function coupon()
    {
        $coupons = Coupon::whereStatus(StatusType::PUBLISHED)->get();
        return CouponResource::collection($coupons);
    }

    public function resellerCoupon()
    {
        $user = auth('reseller-api')->id();
        $resellerCoupon = ResellerCoupon::whereResellerId($user)->where('time_applied', '>', 0)->get();
        return CouponResource::collection($resellerCoupon);
    }

    public function checkCoupon(Request $request)
    {
        $coupon = $request->coupon_name;
        $couponCheck = Coupon::whereName($coupon)->first();
        if (!$couponCheck) {
            return response()->json([
                'status' => "error",
                'message' => "Maaf, kode kupon tidak ditemukan!"
            ], 400);
        } else {
            return response()->json([
                'status' => "success",
                'message' => "Sukses, kode kupon berhasil digunakan!"
            ], 200);
        }
    }

    public function searchProduct(Request $request)
    {
        $products = Product::where('title', 'LIKE', '%' . $request->title . '%')->get();
        return ProductResource::collection($products);
    }

    public function notification(Request $request)
    {
        $notifications = Notification::whereStatus(StatusType::PUBLISHED)->whereNotificationType(strtoupper($request->type))->get();
        return NotificationResource::collection($notifications);
    }

    public function chatSupplier(Request $request)
    {
        $url = "https://api.whatsapp.com/send?phone=.$request->phone_number.&text=Saya ingin bertanya mengenai produk anda";
        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Silahkan klik link!',
                'link' => $url
            ],
        ], 200);
    }

    public function inviteFriends()
    {
        $referalCode = auth('reseller-api')->user()->referal_code;
        // $url = env('APP_REGISTER_RESELLER_URL');
        $url = 'https://malmora.com/register';
        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Silahkan klik link!',
                'link' => "whatsapp://send?text=Silahkan bergabung dengan kami menggunakan kode referal : " . $referalCode . " di link " . $url
            ],
        ], 200);
    }

    public function shareProduct(Request $request)
    {
        $existProduct = ProductReseller::whereProductId($request->product_id)->first();
        $url = env('APP_PRODUCT_DETAIL_URL');
        $category = Product::whereId($request->product_id)->select('category_id')->first();
        $existCategory = CategoryResellerLog::whereCategoryId($category->category_id)->whereResellerId(Auth::guard('reseller-api')->id())->first();
        if (empty($existCategory)) {
            $categoryReseller = new CategoryResellerLog();
            $categoryReseller->reseller_id = Auth::guard('reseller-api')->id();
            $categoryReseller->category_id = $category->category_id;
            $categoryReseller->save();
        }
        $product = new ProductReseller();
        $product->reseller_id = Auth::guard('reseller-api')->id();
        $product->product_id = $request->product_id;
        $product_reseller->category_id = $category->category_id;
        if (!$existProduct) {
            $product->save();
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Silahkan klik link!',
                    'link' => "whatsapp://send?text=Silahkan melihat produk kami : " . $url . "?id=" . $product->id
                ],
            ], 201);
        } else {
            return response()->json([
                'data' => [
                    'status' => 'failed',
                    'data' => "Produk sudah ada di toko anda!"
                ],
            ], 400);
        }
    }

    public function rekeningBersama()
    {
        $admin = User::where('isAdmin', '=', 1)->first();
        $bank_account = BankAccount::with('user')->whereUserId($admin->id)->first();
        return response()->json([
            'data' => $bank_account
        ], 200);
    }

    public function imageUrl()
    {
        return \env('APP_IMAGE_URL');
    }

    public function mediaCode(Request $request)
    {
        $media = Media::whereCode($request->code)->get();
        return response()->json([
            'media' => $media
        ], 200);
    }
}
