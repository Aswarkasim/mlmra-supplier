<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commision;
use Illuminate\Http\Request;

class CommisionController extends Controller
{
    public function index() {
        $commisions = Commision::orderByDesc('id')->paginate(5);
        return view('admin.commision.index', compact('commisions'));
    }
}
