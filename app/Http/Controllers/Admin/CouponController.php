<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActionType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index() {
        $coupons = Coupon::orderByDesc('id')->paginate(5);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create() {
        return view('admin.coupons.create');
    }

    public function save(Request $request) {
        $this->validate($request, [
            'coupon_name' => 'required',
            'sk' => 'required',
            'price' => 'required',
            'point' => 'required',
            'status' => 'required'
        ]);

        $coupon = new Coupon();
        $coupon->name = $request->coupon_name;
        $coupon->term_and_conditions = $request->sk;
        $coupon->price_cut = $request->price;
        $coupon->min_point = $request->point;
        $coupon->status = $request->status;
        $coupon->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan:)','Success');
        return redirect(route('admin.coupon'));
    }

    public function edit($id) {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id) {
        $coupon = Coupon::findOrFail($id);
        $this->validate($request, [
            'coupon_name' => 'required',
            'sk' => 'required',
            'price' => 'required',
            'point' => 'required',
            'status' => 'required'
        ]);

        $coupon->name = $request->coupon_name;
        $coupon->term_and_conditions = $request->sk;
        $coupon->price_cut = $request->price;
        $coupon->min_point = $request->point;
        $coupon->status = $request->status;
        $coupon->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.coupon'));
    }
}
