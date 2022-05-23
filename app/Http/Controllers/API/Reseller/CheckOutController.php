<?php

namespace App\Http\Controllers\API\Reseller;

use App\Enums\ShippingType;
use App\Enums\StatusType;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Product;
use App\Models\ResellerCart;
use App\Models\ResellerCoupon;
use App\Models\ResellerTransaction;
use App\Models\ResellerTransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CheckOutController extends Controller
{

    function addressupdate(Request $request)
    {
        $id = $request->id;
        $reseller_id = auth()->user()->id;
        $address = Address::find($id);
        $anotherAddress = Address::where('reseller_id', $reseller_id)->where('id', '!=', $id)->get();
        // print_r($id);
        // die('masuk');

        // if ($address->status == StatusType::INACTIVE) {
        //     foreach ($anotherAddress as $adress) {
        //         $adress->status = StatusType::INACTIVE;
        //         $adress->save();
        //     }
        //     $address->status = StatusType::ACTIVE;
        //     $adress->save();
        // } else {
        //     $activeAddress = false;
        // }

        $address->status = StatusType::ACTIVE;
        $address->save();

        foreach ($anotherAddress as $item) {
            $item->status = StatusType::INACTIVE;
            $item->save();
        }

        return response()->json([
            'status' => 'Success',
            'anotherAddress' => $anotherAddress,
            'addessActive'  => $address,
            'data' => "Alamat Aktif"
        ], 200);
    }
    public function shippingType(Request $request)
    {
        $sendBySameCity = true;
        $resellerId = Auth::guard('reseller-api')->id();
        $resellerAddress = Address::whereResellerId($resellerId)->whereStatus(StatusType::ACTIVE)->first();
        if (!$resellerAddress) {
            return response()->json([
                'status' => 'failed',
                'data' => "Silahkan isi alamat terlbeih dahulu"
            ], 400);
        }
        $supplierAddress = Address::whereUserId($request->supplier_id)->whereStatus(StatusType::ACTIVE)->first();
        if ($supplierAddress->city_id != $resellerAddress->city_id) {
            $sendBySameCity = false;
        }
        return response()->json([
            ShippingType::PERSONAL_COURIER => true,
            ShippingType::REGULER => true,
            ShippingType::SAME_CITY_DELIVERY => $sendBySameCity
        ], 200);
    }

    public function checkOutCart(Request $request)
    {
        $supplierId = [];
        for ($i = 0; $i < count($request->product_id); $i++) {
            $product = Product::whereId($request->product_id[$i])->first();
            array_push($supplierId, $product->user_id);
            if ($product->status != StatusType::PUBLISHED) {
                return response()->json([
                    'status' => 'failed',
                    'data' => "Mohon maaf, Product " . $product->title . " Sedang tidak tersedia"
                ], 400);
            }
        }


        if (count(array_unique($supplierId)) < count($supplierId)) {
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

    public function transaction(Request $request)
    {
        // dd($request);
        // optional cart_id or product_id, and another
        $resellerId = Auth::guard('reseller-api')->id();
        $resellerAddress = Address::whereResellerId($resellerId)->whereStatus(StatusType::ACTIVE)->first();
        if (!empty($request->kupon_id)) {
            $resellerCoupon = ResellerCoupon::whereResellerId($resellerId)->whereCouponId($request->kupon_id)->first();
            if ($resellerCoupon->time_applied <= 0) {
                return response()->json([
                    'status' => 'failed',
                    'data' => "Mohon maaf, kode kupon anda sudah mencapai batas"
                ], 400);
            }
        }
        if (!$resellerAddress) {
            return response()->json([
                'status' => 'failed',
                'data' => "Silahkan isi alamat terlebih dahulu"
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'bank_account_id' => 'required',
            'courier_type' => 'required',
        ]);

        if ($validator->fails()) {
            $val = ['validation_error' => $validator->errors()];
            return response()->json($val, 400);
        }

        // untuk API Beli dari product
        if (!empty($request->product_id)) {
            // Start transaction
            DB::beginTransaction();
            $waybillNumber = str_pad(rand(0, 999), 5, "0", STR_PAD_LEFT);
            $transaction = new ResellerTransaction();
            $transaction->waybill_number = $waybillNumber;
            $transaction->transaction_code = $waybillNumber;
            $transaction->total_price = $request->total_price;
            $transaction->promo_code = $request->promo_code;
            $transaction->kupon_id = $request->kupon_id;
            $transaction->shipping_method = $request->shipping_method;
            $transaction->custom_courier_name = $request->custom_courier_name;
            $transaction->custom_courier_phone_number = $request->custom_courier_phone_number;
            $transaction->custom_courier_address = $request->custom_courier_address;
            $transaction->courier_type = $request->courier_type;
            $transaction->payment_method = $request->payment_method;
            $transaction->ongkir_type = $request->ongkir_type;
            $transaction->transaction_status = TransactionStatus::UNPAID;
            $transaction->user_id = $request->supplier_id;
            $transaction->reseller_id = $resellerId;
            $transaction->address_name = $request->address_name;
            $transaction->province_id = $request->province_id;
            $transaction->city_id = $request->city_id;
            $transaction->district_id = $request->district_id;
            $transaction->street = $request->street;
            $transaction->remarks = $request->catatan;
            $transaction->bank_account_id = $request->bank_account_id;
            if ($transaction->save()) {
                $lastTransaction = ResellerTransaction::whereWaybillNumber($waybillNumber)->first();
                $productId = Product::whereId($request->product_id)->first();
                $transactionDetail1 = new ResellerTransactionDetail();
                $transactionDetail1->reseller_transaction_id = $lastTransaction->id;
                $transactionDetail1->product_id = $request->product_id;
                $transactionDetail1->order_count = $request->order_count;
                $transactionDetail1->varian_color = $request->varian_color;
                $transactionDetail1->varian_weight = $request->varian_weight;
                $transactionDetail1->varian_size = $request->varian_size;
                $transactionDetail1->varian_type = $request->varian_type;
                $transactionDetail1->varian_taste = $request->varian_taste;
                $transactionDetail1->product_price = $productId->reseller_price;
                $transactionDetail1->save();

                // $this->checkoutStatus($request->product_id);
            }
            if (!$transaction || !$transactionDetail1) {
                DB::rollBack();
                return response()->json([
                    'status' => 'failed',
                    'data' => "Maaf, proses transaksi gagal"
                ], 400);
            } else {
                DB::commit();
                if (!empty($request->kupon_id)) {
                    if ($resellerCoupon) {
                        $resellerCoupon->time_applied -= 1;
                        $resellerCoupon->save();
                    }
                }
                return response()->json([
                    'status' => 'success',
                    'data' => "Transaksi berhasil, silahkan melakukan pembayaran!"
                ], 201);
            }

            // ini untuk beli lagi halaman pesanan diterima
        } else if (!empty($request->transaction_detail_id)) {
            // Start transaction
            DB::beginTransaction();
            $waybillNumber = str_pad(rand(0, 999), 5, "0", STR_PAD_LEFT);
            $transaction = new ResellerTransaction();
            $transaction->waybill_number = $waybillNumber;
            $transaction->transaction_code = $waybillNumber;
            $transaction->total_price = $request->total_price;
            $transaction->promo_code = $request->promo_code;
            $transaction->kupon_id = $request->kupon_id;
            $transaction->shipping_method = $request->shipping_method;
            $transaction->custom_courier_name = $request->custom_courier_name;
            $transaction->custom_courier_phone_number = $request->custom_courier_phone_number;
            $transaction->custom_courier_address = $request->custom_courier_address;
            $transaction->courier_type = $request->courier_type;
            $transaction->payment_method = $request->payment_method;
            $transaction->ongkir_type = $request->ongkir_type;
            $transaction->transaction_status = TransactionStatus::UNPAID;
            $transaction->user_id = $request->supplier_id;
            $transaction->reseller_id = $resellerId;
            $transaction->address_name = $request->address_name;
            $transaction->province_id = $request->province_id;
            $transaction->city_id = $request->city_id;
            $transaction->district_id = $request->district_id;
            $transaction->street = $request->street;
            $transaction->remarks = $request->catatan;
            $transaction->bank_account_id = $request->bank_account_id;
            if ($transaction->save()) {
                $lastTransaction = ResellerTransaction::whereWaybillNumber($waybillNumber)->first();
                for ($i = 0; $i < count($request->transaction_detail_id); $i++) {
                    $productTransaction = ResellerTransactionDetail::whereId($request->transaction_detail_id[$i])->first();
                    $transactionDetail2 = new ResellerTransactionDetail();
                    $transactionDetail2->reseller_transaction_id = $lastTransaction->id;
                    $transactionDetail2->product_id = $productTransaction->product_id;
                    $transactionDetail2->order_count = $productTransaction->order_count;
                    $transactionDetail2->varian_color = $productTransaction->varian_color;
                    $transactionDetail2->varian_weight = $productTransaction->varian_weight;
                    $transactionDetail2->varian_size = $productTransaction->varian_size;
                    $transactionDetail2->varian_type = $productTransaction->varian_type;
                    $transactionDetail2->varian_taste = $productTransaction->varian_taste;
                    $transactionDetail2->product_price = $productTransaction->product_price;
                    $transactionDetail2->save();
                    // $this->checkoutStatus($productTransaction->product_id);
                }
            }
            if (!$transaction || !$transactionDetail2) {
                DB::rollBack();
                return response()->json([
                    'status' => 'failed',
                    'data' => "Maaf, proses transaksi gagal"
                ], 400);
            } else {
                DB::commit();
                if (!empty($request->kupon_id)) {
                    if ($resellerCoupon) {
                        $resellerCoupon->time_applied -= 1;
                        $resellerCoupon->save();
                    }
                }
                return response()->json([
                    'status' => 'success',
                    'data' => "Transaksi berhasil, silahkan melakukan pembayaran!"
                ], 201);
            }
        } else {
            // Start transaction
            DB::beginTransaction();
            $waybillNumber = str_pad(rand(0, 999), 5, "0", STR_PAD_LEFT);
            $transaction = new ResellerTransaction();
            $transaction->waybill_number = $waybillNumber;
            $transaction->transaction_code = $waybillNumber;
            $transaction->total_price = $request->total_price;
            $transaction->promo_code = $request->promo_code;
            $transaction->kupon_id = $request->kupon_id;
            $transaction->shipping_method = $request->shipping_method;
            $transaction->custom_courier_name = $request->custom_courier_name;
            $transaction->custom_courier_phone_number = $request->custom_courier_phone_number;
            $transaction->custom_courier_address = $request->custom_courier_address;
            $transaction->courier_type = $request->courier_type;
            $transaction->payment_method = $request->payment_method;
            $transaction->ongkir_type = $request->ongkir_type;
            $transaction->transaction_status = TransactionStatus::UNPAID;
            $transaction->user_id = $request->supplier_id;
            $transaction->reseller_id = $resellerId;
            $transaction->address_name = $request->address_name;
            $transaction->province_id = $request->province_id;
            $transaction->city_id = $request->city_id;
            $transaction->district_id = $request->district_id;
            $transaction->street = $request->street;
            $transaction->remarks = $request->catatan;
            $transaction->bank_account_id = $request->bank_account_id;
            if ($transaction->save()) {
                $lastTransaction = ResellerTransaction::whereWaybillNumber($waybillNumber)->first();
                for ($i = 0; $i < count($request->cart_id); $i++) {
                    $product = ResellerCart::whereId($request->cart_id[$i])->first();
                    $transactionDetail = new ResellerTransactionDetail();
                    $transactionDetail->reseller_transaction_id = $lastTransaction->id;
                    $transactionDetail->product_id = $product->product_id;
                    $transactionDetail->order_count = $product->order_count;
                    $transactionDetail->varian_color = $product->varian_color;
                    $transactionDetail->varian_weight = $product->varian_weight;
                    $transactionDetail->varian_size = $product->varian_size;
                    $transactionDetail->varian_type = $product->varian_type;
                    $transactionDetail->varian_taste = $product->varian_taste;
                    $transactionDetail->product_price = $product->product->reseller_price;
                    $transactionDetail->save();

                    $this->checkoutStatus($request->cart_id[$i]);
                }
            }
            if (!$transaction || !$transactionDetail) {
                DB::rollBack();
                return response()->json([
                    'status' => 'failed',
                    'data' => "Maaf, proses transaksi gagal"
                ], 400);
            } else {
                DB::commit();
                if (!empty($request->kupon_id)) {
                    if ($resellerCoupon) {
                        $resellerCoupon->time_applied -= 1;
                        $resellerCoupon->save();
                    }
                }
                return response()->json([
                    'status' => 'success',
                    'data' => "Transaksi berhasil, silahkan melakukan pembayaran!"
                ], 201);
            }
        }
    }

    function checkoutStatus($cart_id)
    {
        // $reseller_id = Auth::guard('reseller-api')->id();
        // $cart = ResellerCart::where('reseller_id', $reseller_id)->first();

        $cart = ResellerCart::whereId($cart_id)->first();

        $cart->checkout = true;
        $cart->save();
    }

    public function ongkirReguler(Request $request)
    {
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

    public function cekKota(Request $request)
    {
        $responses = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/city', []);
        $responses->json();
        $responses = json_decode($responses, true);
        return $responses;
    }

    public function cekProv(Request $request)
    {
        $responses = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/province', []);
        $responses->json();
        $responses = json_decode($responses, true);
        return $responses;
    }

    public function cekKec(Request $request)
    {
        $responses = Http::withHeaders([
            'key' => env('RAJAONGKIR_API')
        ])->get('https://pro.rajaongkir.com/api/subdistrict', [
            'city' => $request->city
        ]);
        $responses->json();
        $responses = json_decode($responses, true);
        return $responses;
    }
}
