<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('q');
        $listTestimoni = Testimoni::with(['reseller', 'customer'])->where('description', 'like', "%".$search."%")
            ->orWhere('status', 'like', "%".$search."%")->orderByDesc('id')->paginate(15);
        return view('admin.testimoni.index', compact('listTestimoni'));
    }

    public function block($id) {
        $testimoni = Testimoni::findOrFail($id);
        $testimoni->status = StatusType::BLOCKED;
        $testimoni->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diblock:)','Success');
        return redirect(route('admin.testimoni'));
    }
}
