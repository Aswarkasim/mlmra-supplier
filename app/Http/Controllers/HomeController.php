<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function webLogin(Request $request) {

        dd($request->all());
        $auth = $request->only('email', 'password');

        if (auth()->guard('user')->attempt($auth)) {
            dd($auth);
        }

        return "nooo";
    }
}
