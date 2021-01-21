<?php

namespace App\Http\Controllers;

use App\Models\PaymentWallet;
use Illuminate\Http\Request;
use App\Models\User;

class PaymentWalletController extends Controller
{
    /**
     * Display a listing of payment wallets belonging to a particular user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $payment_wallets = PaymentWallet::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(15);
        $data["payment_wallets"] = $payment_wallets;
        return view('home', $data);
    }


    /**
     * Store a newly created payment wallet resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth()->user();
        $payment_wallet = new PaymentWallet();
        $payment_wallet->name = $request->name;
        $payment_wallet->description = $request->description;
        $payment_wallet->currency = $request->currency;

        if($user->PaymentWallets()->save($payment_wallet))
        {
            $message = "Your wallet '".$payment_wallet->name."' was created successfully";
            $data['message'] = $message;
            return redirect('home')->with('message', $message);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentWallet  $paymentWallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentWallet $paymentWallet)
    {
        //
    }

    /**
     * Remove the specified payment wallet from storage temporarily (soft delete).
     *
     * @param  \App\Models\PaymentWallet  $paymentWallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentWallet $paymentWallet)
    {
        $message = "Your wallet '".$paymentWallet->name."' has been deleted";
        if($paymentWallet->delete())
        {
            return redirect('home')->with('message', $message);
        }
    }
}
