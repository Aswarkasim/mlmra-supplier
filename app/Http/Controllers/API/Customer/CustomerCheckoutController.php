<?php

namespace App\Http\Controllers\API\Customer;

use App\Enums\ShippingType;
use App\Enums\StatusType;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ResellerCart;
use App\Models\ResellerCoupon;
use App\Models\ResellerTransaction;
use App\Models\ResellerTransactionDetail;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CustomerCheckoutController extends Controller
{
    public function shippingType(Request $request) {
        $sendBySameCity = true;
        $customerId = Auth::guard('customer-api')->id();
        $customerAddress = Address::whereCustomerId($customerId)->whereStatus(StatusType::ACTIVE)->first();
        if (!$customerAddress) {
            return response()->json([
                'status' => 'failed',
                'data' => "Silahkan isi alamat terlbeih dahulu"
            ], 400  );
        }
        $supplierAddress = Address::whereUserId($request->supplier_id)->whereStatus(StatusType::ACTIVE)->first();
        if ($supplierAddress->city_id != $customerAddress->city_id) {
            $sendBySameCity = false;
        }
        return response()->json([
            ShippingType::PERSONAL_COURIER => true,
            ShippingType::REGULER => true,
            ShippingType::SAME_CITY_DELIVERY => $sendBySameCity
        ],200);
    }

    public function checkOutCart(Request $request) {
        $supplierId = [];
        for($i = 0; $i < count($request->product_id); $i++) {
            $product = Product::whereId($request->product_id[$i])->first();
            array_push($supplierId, $product->user_id);
            if ($product->status != StatusType::PUBLISHED) {
                return response()->json([
                    'status' => 'failed',
                    'data' => "Mohon maaf, Product " .$product->title. " Sedang tidak tersedia"
                ], 400);
            }
        }

        if(count(array_unique($supplierId)) != 1){
            return response()->json([
                'status' => 'failed',
                'data' => "Mohon maaf, Hanya bisa membeli dari 1 toko!"
            ], 400);
        }

        return response()->json([
            'status' => "success",
            'message' => "Berhasil"
        ], 200);
    }

    public function transaction(Request $request) {
        // optional cart_id or product_id, or another
        $customerId = Auth::guard('customer-api')->id();
        $resellerAddress = Address::whereCustomerId($customerId)->whereStatus(StatusType::ACTIVE)->first();
        if (!$resellerAddress) {
            return response()->json([
                'status' => 'failed',
                'data' => "Silahkan isi alamat terlebih dahulu"
            ], 400);
        }

        // untuk API Beli dari product
        if (!empty($request->product_id)) {
            // Start transaction
            DB::beginTransaction();
            $waybillNumber = str_pad(rand(0,999), 5, "0", STR_PAD_LEFT);
            $transaction = new Transaction();
            $transaction->waybill_number = $waybillNumber;
            $transaction->transaction_code = $waybillNumber;
            $transaction->total_price = $request->total_price;
            $transaction->promo_code = $request->promo_code;
            $transaction->kupon_id = $request->kupon_id;
            $transaction->shipping_method = $request->shipping_method;
            $transaction->custom_courier_name = $request->custom_courier_name;
            $transaction->custom_courier_phone_number = $request->custom_courier_phone_number;
            $transaction->courier_type = $request->courier_type;
            $transaction->payment_method = $request->payment_method;
            $transaction->ongkir_type = $request->ongkir_type;
            $transaction->transaction_status = TransactionStatus::UNPAID;
            $transaction->user_id = $request->supplier_id;
            $transaction->customer_id = $customerId;
            $transaction->address_name = $request->address_name;
            $transaction->province_id = $request->province_id;
            $transaction->city_id = $request->city_id;
            $transaction->district_id = $request->district_id;
            $transaction->street = $request->street;
            $transaction->remarks = $request->catatan;
            if ($transaction->save()) {
                $lastTransaction = Transaction::whereWaybillNumber($waybillNumber)->first();
                $productId = Product::whereId($request->product_id)->first();
                $transactionDetail1 = new TransactionDetail();
                $transactionDetail1->transaction_id = $lastTransaction->id;
                $transactionDetail1->product_id = $request->product_id;
                $transactionDetail1->varian_color = $request->varian_color;
                $transactionDetail1->varian_weight = $request->varian_weight;
                $transactionDetail1->varian_size = $request->varian_size;
                $transactionDetail1->varian_type = $request->varian_type;
                $transactionDetail1->varian_taste = $request->varian_taste;
                $transactionDetail1->product_price = $productId->reseller_price;
                $transactionDetail1->save();

            }
            if( !$transaction || !$transactionDetail1 )
            {
                DB::rollBack();
                return response()->json([
                    'status' => 'failed',
                    'data' => "Maaf, proses transaksi gagal"
                ], 400);
            } else {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'data' => "Transaksi berhasil, silahkan melakukan pembayaran!"
                ], 201);
            }

            // ini untuk beli lagi halaman pesanan diterima
        } else if(!empty($request->transaction_detail_id)) {
            // Start transaction
            DB::beginTransaction();
            $waybillNumber = str_pad(rand(0,999), 5, "0", STR_PAD_LEFT);
            $transaction = new Transaction();
            $transaction->waybill_number = $waybillNumber;
            $transaction->transaction_code = $waybillNumber;
            $transaction->total_price = $request->total_price;
            $transaction->promo_code = $request->promo_code;
            $transaction->kupon_id = $request->kupon_id;
            $transaction->shipping_method = $request->shipping_method;
            $transaction->custom_courier_name = $request->custom_courier_name;
            $transaction->custom_courier_phone_number = $request->custom_courier_phone_number;
            $transaction->courier_type = $request->courier_type;
            $transaction->payment_method = $request->payment_method;
            $transaction->ongkir_type = $request->ongkir_type;
            $transaction->transaction_status = TransactionStatus::UNPAID;
            $transaction->user_id = $request->supplier_id;
            $transaction->customer_id = $customerId;
            $transaction->address_name = $request->address_name;
            $transaction->province_id = $request->province_id;
            $transaction->city_id = $request->city_id;
            $transaction->district_id = $request->district_id;
            $transaction->street = $request->street;
            $transaction->remarks = $request->catatan;
            if ($transaction->save()) {
                $lastTransaction = Transaction::whereWaybillNumber($waybillNumber)->first();
                for($i = 0; $i < count($request->transaction_detail_id); $i++) {
                    $productTransaction = TransactionDetail::whereId($request->transaction_detail_id[$i])->first();
                    $transactionDetail2 = new TransactionDetail();
                    $transactionDetail2->transaction_id = $lastTransaction->id;
                    $transactionDetail2->product_id = $productTransaction->product_id;
                    $transactionDetail2->varian_color = $productTransaction->varian_color;
                    $transactionDetail2->varian_weight = $productTransaction->varian_weight;
                    $transactionDetail2->varian_size = $productTransaction->varian_size;
                    $transactionDetail2->varian_type = $productTransaction->varian_type;
                    $transactionDetail2->varian_taste = $productTransaction->varian_taste;
                    $transactionDetail2->product_price = $productTransaction->product_price;
                    $transactionDetail2->save();
                }
            }
            if( !$transaction || !$transactionDetail2 )
            {
                DB::rollBack();
                return response()->json([
                    'status' => 'failed',
                    'data' => "Maaf, proses transaksi gagal"
                ], 400);
            } else {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'data' => "Transaksi berhasil, silahkan melakukan pembayaran!"
                ], 201);
            }
        } else {
            // Start transaction
            DB::beginTransaction();
            $waybillNumber = str_pad(rand(0,999), 5, "0", STR_PAD_LEFT);
            $transaction = new Transaction();
            $transaction->waybill_number = $waybillNumber;
            $transaction->transaction_code = $waybillNumber;
            $transaction->total_price = $request->total_price;
            $transaction->promo_code = $request->promo_code;
            $transaction->kupon_id = $request->kupon_id;
            $transaction->shipping_method = $request->shipping_method;
            $transaction->custom_courier_name = $request->custom_courier_name;
            $transaction->custom_courier_phone_number = $request->custom_courier_phone_number;
            $transaction->courier_type = $request->courier_type;
            $transaction->payment_method = $request->payment_method;
            $transaction->ongkir_type = $request->ongkir_type;
            $transaction->transaction_status = TransactionStatus::UNPAID;
            $transaction->user_id = $request->supplier_id;
            $transaction->customer_id = $customerId;
            $transaction->address_name = $request->address_name;
            $transaction->province_id = $request->province_id;
            $transaction->city_id = $request->city_id;
            $transaction->district_id = $request->district_id;
            $transaction->street = $request->street;
            $transaction->remarks = $request->catatan;
            if ($transaction->save()) {
                $lastTransaction = Transaction::whereWaybillNumber($waybillNumber)->first();
                for($i = 0; $i < count($request->cart_id); $i++) {
                    $product = Cart::whereId($request->cart_id[$i])->first();
                    $productId = $product->product_reseller->product->id;
                    $transactionDetail = new TransactionDetail();
                    $transactionDetail->transaction_id = $lastTransaction->id;
                    $transactionDetail->product_id = $productId;
                    $transactionDetail->varian_color = $product->varian_color;
                    $transactionDetail->varian_weight = $product->varian_weight;
                    $transactionDetail->varian_size = $product->varian_size;
                    $transactionDetail->varian_type = $product->varian_type;
                    $transactionDetail->varian_taste = $product->varian_taste;;
                    $transactionDetail->product_price = $product->product_reseller->reseller_price;
                    $transactionDetail->save();
                }
            }
            if( !$transaction || !$transactionDetail )
            {
                DB::rollBack();
                return response()->json([
                    'status' => 'failed',
                    'data' => "Maaf, proses transaksi gagal"
                ], 400);
            } else {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'data' => "Transaksi berhasil, silahkan melakukan pembayaran!"
                ], 201);
            }
        }
    }

    public function ongkirReguler(Request $request) {
        $responses = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->post('https://pro.rajaongkir.com/api/cost', [
            'origin' => $request->origin,
            'originType' => $request->originType,
            'destination' => $request->destination,
            'destinationType' => $request->destinationType,
            'courier' => $request->courier,
            'weight' => $request->weight
        ]);
        $responses->json();
        $responses = json_decode($responses, true);
        return $responses;
    }
}
