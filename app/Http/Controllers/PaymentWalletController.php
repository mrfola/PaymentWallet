<?php

namespace App\Http\Controllers;

use App\Models\PaymentWallet;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserTransaction;

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
            return redirect('home')->with('wallet_message', $message);
        }
    }


    public function top_up_wallet(int $topUpAmount, PaymentWallet $paymentWallet)
    {
        $currentWalletBalance = (int) $paymentWallet->account_balance;
        $newWalletBalance = $currentWalletBalance + (int) $topUpAmount;

        if($paymentWallet->update(["account_balance" => $newWalletBalance]))
        {
            $message = "Your funds have been added. Your new wallet balance for '".$paymentWallet->name."' is: ".$newWalletBalance;
            $data['message'] = $message;
            return redirect('home')->with('wallet_message', $message);
        }
    }


    public function withdraw_from_wallet(Request $request)
    {
        $withdrawAmount = $request->amount;
        $paymentWallet = PaymentWallet::findOrFail($request->wallet_id);

        //check that you have enough money to withdraw
        if($withdrawAmount <= $paymentWallet->account_balance)
        {
            //create debit transaction record
            $newDebitTransaction = new UserTransaction();
            $newDebitTransaction->amount = $withdrawAmount;
            $newDebitTransaction->ip_address = '1.0.0.1';
            $newDebitTransaction->payment_wallet_id = $paymentWallet->id;
            $newDebitTransaction->transaction_type = "debit";

            if($newDebitTransaction->save())
            {
                $currentWalletBalance = (int) $paymentWallet->account_balance;
                $newWalletBalance = $currentWalletBalance - (int) $withdrawAmount;
                if($paymentWallet->update(["account_balance" => $newWalletBalance]))
                {
                    $message = "Your funds have been deducted. Your new wallet balance for '".$paymentWallet->name."' is: ".$newWalletBalance;
                    $data['wallet_message'] = $message;
                    return redirect('home')->with('wallet_message', $message);
                }
            }
        }else
        {
            $error = "You don't have sufficient funds to withdraw #".$withdrawAmount." from your wallet '". $paymentWallet->name."'";
            return redirect('home')->with('wallet_error', $error);
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
    public function destroy(Request $request)
    {
        $payment_wallet_id = $request->wallet_id;
        $paymentWallet = PaymentWallet::findOrFail($payment_wallet_id);
        $message = "Your wallet '".$paymentWallet->name."' has been deleted";
        if($paymentWallet->delete())
        {
            return redirect('home')->with('wallet_message', $message);
        }
    }
}
