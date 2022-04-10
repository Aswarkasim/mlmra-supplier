<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BankType;
use App\Enums\RoleType;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('q');
        $listBankAccount = BankAccount::whereUserId(Auth::user()->id)->where('account_name', 'like', "%".$search."%")->orderByDesc('id')->paginate(15);
        return view('admin.bank.index', compact('listBankAccount'));
    }

    public function indexAdmin(Request $request) {
        $search = $request->get('q');
        $listBankAccount = BankAccount::where('account_name', 'like', "%".$search."%")->orderByDesc('id')->paginate(15);
        return view('admin.bank.index', compact('listBankAccount'));
    }

    public function create() {
        $banks = BankType::getValues();
        return view('admin.bank.create', compact('banks'));
    }

    public function save(Request $request) {
        $this->validate($request, [
            'account_name' => 'required',
            'account_number' => 'required|unique:bank_accounts|min:10|max:15',
            'bank_name' => 'required'
        ]);
        $bankAccount = new BankAccount();
        $bankAccount->account_name = $request->account_name;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->user_id = Auth::id();
        $bankAccount->user_type = RoleType::SUPPLIER;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan :)','Success');
        return redirect(route('admin.bankAccount'));
    }

    public function edit($id) {
        $banks = BankType::getValues();
        $bankAccount = BankAccount::find($id);
        return view('admin.bank.edit', compact('bankAccount','banks'));
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'account_name' => 'required',
            'account_number' => 'required|min:10|max:15|unique:bank_accounts,account_number,'.$id,
            'bank_name' => 'required'
        ]);
        $bankAccount = BankAccount::findOrFail($id);
        $bankAccount->account_name = $request->account_name;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->user_id = Auth::id();
        $bankAccount->user_type = RoleType::SUPPLIER;
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate :)','Success');
        return redirect(route('admin.bankAccount'));
    }


}
