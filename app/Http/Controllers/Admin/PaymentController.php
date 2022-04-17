<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Reseller;
use App\Models\ResellerTransaction;
use App\Models\ResellerTransactionDetail;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function resellerPayment()
    {
        $listResellerPayment = Payment::whereNotNull('reseller_transaction_id')->paginate(5);
        return view('admin.payment.reseller', compact('listResellerPayment'));
    }

    public function customerPayment()
    {
        $listCustomerPayment = Payment::whereNotNull('transaction_id')->paginate(5);
        return view('admin.payment.customer', compact('listCustomerPayment'));
    }

    public function confirm($id)
    {
        $payment = Payment::whereId($id)->first();

        return view('admin.payment.confirm', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::whereId($id)->first();
        $payment->status = $request->status;
        $payment->save();
        if ($request->status == PaymentStatus::PAID) {
            $transaction = ResellerTransaction::whereId($payment->reseller_transaction_id)->first();
            $transaction->transaction_status = TransactionStatus::PROCESS;
            $transaction->save();

            $reseller_transaction_detail = ResellerTransactionDetail::where('reseller_transaction_id', $payment->reseller_transaction_id)->get();
            // dd($reseller_transaction_detail);
            // die;
            foreach ($reseller_transaction_detail as $rtd) {
                $product = Product::find($rtd->product_id);
                $product->amount_sold = $product->amount_sold + $rtd->product_id;
                $product->save();
            }
        }


        \Brian2694\Toastr\Facades\Toastr::success('Berhasil dikonfirmasi:)', 'Info');
        return redirect(route('admin.resellerPayment'));
    }
}
