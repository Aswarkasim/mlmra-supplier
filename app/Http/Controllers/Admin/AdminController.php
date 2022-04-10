<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Reseller;
use App\Models\ResellerTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() {
        $user  = Auth::user();
        if (Auth::user()->isAdmin) {
            $totalProduct = Product::whereStatus(StatusType::PUBLISHED)->count();
            $totalCustomerTransaksi = Transaction::whereTransactionStatus(PaymentStatus::PAID)->count();
            $totalResellerTransaksi = ResellerTransaction::whereTransactionStatus(PaymentStatus::PAID)->count();
            $totalSupplier = User::whereStatus(StatusType::ACTIVE)->count();
            $totalReseller = Reseller::whereStatus(StatusType::ACTIVE)->count();
            $totalCustomer = Customer::whereStatus(StatusType::ACTIVE)->count();
            return view('admin.dashboard.index', compact('totalCustomer', 'totalProduct', 'totalSupplier', 'totalReseller', 'totalResellerTransaksi', 'totalCustomerTransaksi','user'));
        } else {
            $totalProduct = Product::whereUserId($user->id)->whereStatus(StatusType::PUBLISHED)->count();
            $totalCustomerTransaksi = Transaction::whereStatus(PaymentStatus::PAID)->count();
            $totalResellerTransaksi = ResellerTransaction::whereTransactionStatus(PaymentStatus::PAID)->count();
            return view('admin.dashboard.index', compact('totalProduct', 'totalResellerTransaksi', 'totalCustomerTransaksi','user'));
        }

    }
}
