<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentWalletController;

class HomeController extends PaymentWalletController
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
    public function home_index()
    {
        $user = Auth()->user();
        return $this->index($user);
        //return view('home');
    }
}
