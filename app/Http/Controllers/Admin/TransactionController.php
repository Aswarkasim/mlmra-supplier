<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ResellerTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function resellerTransaction()
    {
        $listResellerTransaction = ResellerTransaction::paginate(5);
        return view('admin.transaction.reseller', compact('listResellerTransaction'));
    }

    public function customerTransaction()
    {
        $listCustomerTransaction = Transaction::paginate(5);
        return view('admin.transaction.customer', compact('listCustomerTransaction'));
    }

    public function confirmation($id, $status)
    {
        $transaction = ResellerTransaction::whereId($id)->first();
        if ($status == TransactionStatus::CANCEL || $status == TransactionStatus::RETURNED) {
            \Brian2694\Toastr\Facades\Toastr::success('Maaf status tidak bisa diubah lagi:)', 'Info');
            return redirect()->back();
        } elseif ($status == TransactionStatus::SENT) {
            $transaction->transaction_status = TransactionStatus::DONE;
            $transaction->save();
            \Brian2694\Toastr\Facades\Toastr::success('Status berhasil diubah:)', 'Info');
            return redirect()->back();
        } elseif ($status == TransactionStatus::DONE) {
            $transaction->transaction_status = TransactionStatus::RETURNED;
            $transaction->save();
            \Brian2694\Toastr\Facades\Toastr::success('Status berhasil diubah:)', 'Info');
            return redirect()->back();
        } else {
            $isAdmin = Auth::user()->isAdmin;
            if (!$isAdmin) {
                \Brian2694\Toastr\Facades\Toastr::success('Silahkan tunggu admin mengonfirmasi pembayaran:)', 'Info');
                return redirect()->back();
            } else {
                $payment = Payment::whereResellerTransactionId($transaction->id)->first();
                if (!$payment) {
                    \Brian2694\Toastr\Facades\Toastr::success('Maaf pembayaran belum tersedia:)', 'Info');
                    return redirect()->back();
                }
                return redirect()->action(
                    [PaymentController::class, 'confirm'],
                    ['id' => $payment->id]
                );
            }
        }
    }

    public function processConfirmation(Request $request)
    {
        $transaction = ResellerTransaction::whereId($request->transactionId)->first();
        $transaction->transaction_status = $request->status;
        $transaction->save();
        \Brian2694\Toastr\Facades\Toastr::success('Sukses ubah status:)', 'Info');
        return redirect()->back();
    }

    function updateResi(Request $request)
    {
        // dd($request->all());
        $transaction = ResellerTransaction::whereId($request->transactionIdResi)->first();
        $transaction->resi = $request->resi;
        $transaction->save();
        \Brian2694\Toastr\Facades\Toastr::success('Sukses ubah resi:)', 'Info');
        return redirect()->back();
    }


    //onclick information ubah status
}
