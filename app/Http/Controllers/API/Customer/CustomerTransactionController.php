<?php

namespace App\Http\Controllers\API\Customer;

use App\Enums\CategoryType;
use App\Enums\MediaType;
use App\Enums\PaymentStatus;
use App\Enums\StatusType;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Payment;
use App\Models\ResellerTransaction;
use App\Models\ResellerTransactionDetail;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\AdminGeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\TransactionDetail;

class CustomerTransactionController extends Controller
{
    use AdminGeneralTrait;

    static function getCustomerId() {
        return Auth::guard('customer-api')->id();
    }

    public function unpaid() {
        $unpaid = Transaction::with( 'user','customer','coupon','transaction_detail','transaction_detail.product')->whereTransactionStatus(TransactionStatus::UNPAID)
            ->whereCustomerId(Auth::guard('customer-api')->id())->get();
        return TransactionResource::collection($unpaid);
//        return response()->json([
//            'data' => [
//                'transaction' => $unpaid,
//                ''
//            ]
//        ], 200);
    }

    public function payment(Request $request) {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required',
            'image' => 'required'
        ]);
        if($validator->fails()){
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }
        $media = new Media();
        $payment = new Payment();
        $media = $this->uploadMedia($request->image, MediaType::IMAGE, CategoryType::PAYMENT, null);
        $payment->transaction_id = $request->transaction_id;
        $payment->status = PaymentStatus::CONFIRMATION;
        $payment->media_id = $media->id;
        $request->file('image')->store('storage');
        $payment->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil, silahkan tunggu pengecekan konfirmasi pembayaran anda!'
        ], 201);
    }

    public function process() {
        $process = Transaction::with( 'user','customer','coupon','transaction_detail','transaction_detail.product')->whereTransactionStatus(TransactionStatus::PROCESS)
            ->whereCustomerId(Auth::guard('customer-api')->id())->get();
        return TransactionResource::collection($process);
    }

    public function cancelTransaction(Request $request) {
        $reseller_transaction = Transaction::whereId($request->transaction_id)->first();
        $reseller_transaction->transaction_status = TransactionStatus::CANCEL;
        $reseller_transaction->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dibatalkan!'
        ], 201);
    }

    public function sent() {
        $sent = Transaction::with( 'user','customer','coupon','transaction_detail','transaction_detail.product')->whereTransactionStatus(TransactionStatus::SENT)
            ->whereCustomerId(Auth::guard('customer-api')->id())->get();
        return TransactionResource::collection($sent);
    }

    public function beli(Request $request) {
        $sent = Transaction::with( 'user','customer','coupon','transaction_detail','transaction_detail.product')->whereTransactionStatus(TransactionStatus::SENT)->whereId($request->transaction_id)->first();
        return response()->json([
            'data' => $sent
        ], 200);
    }

    public function pesananDiterima(Request $request) {
        $transaction = Transaction::whereId($request->transaction_id)->first();
        $countProduct = count(ResellerTransactionDetail::whereResellerTransactionId($request->transaction_id)->get());
        $transaction->transaction_status = TransactionStatus::DONE;
        $transaction->save();

        $user = User::whereId($transaction->user_id)->first();
        $user->total_product_sold += $countProduct;
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil diterima!'
        ], 201);
    }

    public function trackTransaction(Request $request) {
        $responses = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->post('https://pro.rajaongkir.com/api/waybill', [
            'waybill' => $request->waybill,
            'courier' => $request->courier_code
        ]);
        $responses->json();
        $responses = json_decode($responses, true);
        return $responses;
    }

    public function done() {
        $done = Transaction::with( 'user','customer','coupon','transaction_detail','transaction_detail.product')->whereTransactionStatus(TransactionStatus::DONE)
            ->whereCustomerId(Auth::guard('customer-api')->id())->get();
        return TransactionResource::collection($done);
    }

    public function tambahUlasan(Request $request) {
        $supplierId = null;
        for($i = 0; $i < count($request->transaction_detail_id); $i++) {
            $transactionDetail = TransactionDetail::whereId($request->transaction_detail_id[$i])->first();
            $comment = new Comment();
            $comment->product_id = $transactionDetail->product_id;
            $comment->customer_id = self::getCustomerId();
            $comment->comment = $request->comment[$i];
            $comment->rating = $request->rating[$i];
            $comment->status = StatusType::PUBLISHED;
            $comment->save();

            $user = User::whereId($transactionDetail->transaction->user_id)->first();
            $supplierId = $user->id;
            $user->total_ulasan += 1;
            $user->save();

        }

        // $ratingQuality = User::with('product.comment:rating')->select('rating')->get();
        $ratings = Comment::with('product')->whereHas('product' , function ($query) use($supplierId) {
            $query->whereUserId($supplierId);
        })->select('rating')->get();

        $count = 0;
        $ratingSum = 0;
        foreach ($ratings as $rating) {
            $ratingSum += $rating->rating;
            $count += 1;
        }
        $ratingAvg = $ratingSum / $count;

        $userRatingQuality = User::whereId($supplierId)->first();
        $userRatingQuality->product_quality_rating = $ratingAvg;
        $userRatingQuality->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Ulasan berhasil dikirim'
        ], 201);

    }

    public function cancel() {
        $cancel = Transaction::with( 'user','customer','coupon','transaction_detail','transaction_detail.product')->whereTransactionStatus(TransactionStatus::CANCEL)
            ->whereCustomerId(Auth::guard('customer-api')->id())->get();
        return TransactionResource::collection($cancel);
    }

    public function returned() {
        $returned = Transaction::with( 'user','customer','coupon','transaction_detail','transaction_detail.product')->whereTransactionStatus(TransactionStatus::RETURNED)
            ->whereCustomerId(Auth::guard('customer-api')->id())->get();
        return TransactionResource::collection($returned);
    }

    public function image(Request $request) {
        $thumbnailTransaction = Media::whereCode($request->media_code)->first();
        return response()->json([
            'data' => $thumbnailTransaction
        ], 200);
    }
}
