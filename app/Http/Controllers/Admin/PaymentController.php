<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ResellerTransaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function resellerPayment() {
        $listResellerPayment = Payment::whereNotNull('reseller_transaction_id')->paginate(5);
        return view('admin.payment.reseller', compact('listResellerPayment'));
    }

    public function customerPayment() {
        $listCustomerPayment = Payment::whereNotNull('transaction_id')->paginate(5);
        return view('admin.payment.customer', compact('listCustomerPayment'));
    }

    public function confirm($id) {
        $payment = Payment::whereId($id)->first();
        return view('admin.payment.confirm', compact('payment'));
    }

    public function update(Request $request, $id) {
        $payment = Payment::whereId($id)->first();
        $payment->status = $request->status;
        $payment->save();
        if ($request->status == PaymentStatus::PAID) {
            $transaction = ResellerTransaction::whereId($payment->reseller_transaction_id)->first();
            $transaction->transaction_status = TransactionStatus::PROCESS;
            $transaction->save();
        }
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil dikonfirmasi:)','Info');
        return redirect(route('admin.resellerPayment'));
    }
}
