<?php

namespace App\Http\Controllers\API\Reseller;

use App\Models\User;
use App\Models\Media;
use Ramsey\Uuid\Uuid;
use App\Models\Comment;
use App\Models\Payment;
use App\Enums\MediaType;
use App\Enums\StatusType;
use App\Enums\CategoryType;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Enums\TransactionStatus;
use App\Traits\AdminGeneralTrait;
use App\Models\ResellerTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\ResellerTransactionDetail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    use AdminGeneralTrait;

    static function getResellerId()
    {
        return Auth::guard('reseller-api')->id();
    }

    function count_order()
    {
        $reseller_id = request('reseller_id');
        $status = request('transaction_status');
        // $reseller_id = 3;

        if ($status) {
            $order = ResellerTransaction::whereResellerId($reseller_id)->where('transaction_status', $status)->get();
        } else {
            $order = ResellerTransaction::whereResellerId($reseller_id)->get();
        }

        return response()->json([
            'data' => [
                'status'    => 'Success',
                'total' => count($order),
            ]
        ], 200);
    }

    public function unpaid()
    {
        $unpaid = ResellerTransaction::with('user', 'reseller', 'coupon', 'reseller_transaction_detail')->whereTransactionStatus(TransactionStatus::UNPAID)
            ->whereResellerId(Auth::guard('reseller-api')->id())->get();
        return TransactionResource::collection($unpaid);
        //        return response()->json([
        //            'data' => [
        //                'transaction' => $unpaid,
        //                ''
        //            ]
        //        ], 200);
    }

    public function unpaid_single(Request $request)
    {
        $unpaid = ResellerTransaction::with('user', 'reseller', 'coupon', 'reseller_transaction_detail', 'reseller_transaction_detail.product', 'bank_account')->whereTransactionStatus(TransactionStatus::UNPAID)
            ->whereId($request->transaction_id)
            ->whereResellerId(Auth::guard('reseller-api')->id())->get();
        return TransactionResource::collection($unpaid);
        //        return response()->json([
        //            'data' => [
        //                'transaction' => $unpaid,
        //                ''
        //            ]
        //        ], 200);
    }

    public function payment(Request $request)
    {
        // die('masuk');

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required',
            'image' => 'required'
        ]);
        if ($validator->fails()) {
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }
        $media = new Media();
        $payment = new Payment();

        // $media = $this->uploadMedia($request->image, MediaType::IMAGE, CategoryType::PAYMENT, null);

        // $media = $request->file('image');
        $media = $request->file('image');
        // dd($media);
        $uuid = Uuid::uuid4()->toString();
        $uuid2 = Uuid::uuid4()->toString();
        $fileSize = $media->getSize();

        // $file_name = time() . "_" . $media->getClientOriginalName();
        // $file_name = $uuid . '-' . $uuid2 . '.' . $fileType;
        $fileType = $media->getClientOriginalName();
        $file_name = $uuid . '-' . $uuid2 .  $fileType;
        $storage = 'uploads/images/';
        $media->move($storage, $file_name);

        $media = Media::create([
            'file_name' => $file_name,
            'media_type' => MediaType::IMAGE,
            'file_size' => $fileSize,
            'code'      => null,
            'path'      => $storage,
            'category_type' => CategoryType::SUPPLIER
        ]);

        $payment->reseller_transaction_id = $request->transaction_id;
        $payment->status = PaymentStatus::CONFIRMATION;
        $payment->media_id = $media->id;
        // $request->file('image')->store('storage');


        $payment->save();

        $transaction = ResellerTransaction::find($request->transaction_id);
        // print_r($transaction);
        $transaction->transaction_status = TransactionStatus::PROCESS;
        $transaction->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil, silahkan tunggu pengecekan konfirmasi pembayaran anda!',
            'data'     => $payment
        ], 201);
    }

    public function process()
    {
        $process = ResellerTransaction::with('user', 'reseller', 'coupon', 'reseller_transaction_detail', 'reseller_transaction_detail.product')->whereTransactionStatus(TransactionStatus::PROCESS)
            ->whereResellerId(Auth::guard('reseller-api')->id())->get();
        return TransactionResource::collection($process);
    }

    public function cancelTransaction(Request $request)
    {
        $reseller_transaction = ResellerTransaction::whereId($request->transaction_id)->first();
        $reseller_transaction->transaction_status = TransactionStatus::CANCEL;
        $reseller_transaction->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dibatalkan!'
        ], 201);
    }

    public function sent()
    {
        $sent = ResellerTransaction::with('user', 'reseller', 'coupon', 'reseller_transaction_detail', 'reseller_transaction_detail.product')->whereTransactionStatus(TransactionStatus::SENT)
            ->whereResellerId(Auth::guard('reseller-api')->id())->get();
        return TransactionResource::collection($sent);
    }

    public function beli(Request $request)
    {
        $sent = ResellerTransaction::with('user', 'reseller', 'coupon', 'reseller_transaction_detail', 'reseller_transaction_detail.product')->whereTransactionStatus(TransactionStatus::SENT)->whereId($request->transaction_id)->first();
        return response()->json([
            'data' => $sent
        ], 200);
    }

    public function pesananDiterima(Request $request)
    {
        $reseller_transaction = ResellerTransaction::whereId($request->transaction_id)->first();
        $countProduct = count(ResellerTransactionDetail::whereResellerTransactionId($request->transaction_id)->get());
        $reseller_transaction->transaction_status = TransactionStatus::DONE;
        $reseller_transaction->save();

        $user = User::whereId($reseller_transaction->user_id)->first();
        $user->total_product_sold += $countProduct;
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil diterima!'
        ], 201);
    }

    public function trackTransaction(Request $request)
    {
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

    public function done()
    {
        $done = ResellerTransaction::with('user', 'reseller', 'coupon', 'reseller_transaction_detail', 'reseller_transaction_detail.product')->whereTransactionStatus(TransactionStatus::DONE)
            ->whereResellerId(Auth::guard('reseller-api')->id())->get();
        return TransactionResource::collection($done);
    }

    public function tambahUlasan(Request $request)
    {
        $supplierId = null;
        for ($i = 0; $i < count($request->transaction_detail_id); $i++) {
            $transactionDetail = ResellerTransactionDetail::whereId($request->transaction_detail_id[$i])->first();
            $comment = new Comment();
            $comment->product_id = $transactionDetail->product_id;
            $comment->reseller_id = self::getResellerId();
            $comment->comment = $request->comment[$i];
            $comment->rating = $request->rating[$i];
            $comment->status = StatusType::PUBLISHED;
            $comment->save();

            $user = User::whereId($transactionDetail->reseller_transaction->user_id)->first();
            $supplierId = $user->id;
            $user->total_ulasan += 1;
            $user->save();
        }

        // $ratingQuality = User::with('product.comment:rating')->select('rating')->get();
        $ratings = Comment::with('product')->whereHas('product', function ($query) use ($supplierId) {
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

    public function cancel()
    {
        $cancel = ResellerTransaction::with('user', 'reseller', 'coupon', 'reseller_transaction_detail', 'reseller_transaction_detail.product')->whereTransactionStatus(TransactionStatus::CANCEL)
            ->whereResellerId(Auth::guard('reseller-api')->id())->get();
        return TransactionResource::collection($cancel);
    }

    public function returned()
    {
        $returned = ResellerTransaction::with('user', 'reseller', 'coupon', 'reseller_transaction_detail', 'reseller_transaction_detail.product')->whereTransactionStatus(TransactionStatus::RETURNED)
            ->whereResellerId(Auth::guard('reseller-api')->id())->get();
        return TransactionResource::collection($returned);
    }

    public function image(Request $request)
    {
        $thumbnailTransaction = Media::whereCode($request->media_code)->first();
        return response()->json([
            'data' => $thumbnailTransaction
        ], 200);
    }
}
