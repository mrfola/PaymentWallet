<?php

namespace App\Http\Controllers;

use Paystack;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\PaymentWallet;
use App\Models\UserTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\PaymentInterface;
use App\Http\Controllers\PaymentWalletController;

class PaystackPaymentController extends PaymentWalletController implements PaymentInterface
{
    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway()
    {
        $request = request();
        $request['amount'] = $request['amount'] * 100; //convert amount to kobo for paystack processing

        try
        {
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e)
        {
            return Redirect::back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        }        
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        //dd($paymentDetails);

        $creditTransaction = new UserTransaction();
        $creditTransaction->payment_wallet_id = $paymentDetails['data']['metadata']['wallet_id'];
        $creditTransaction->amount = $paymentDetails['data']['amount']/100; //convert amount back to naira
        $creditTransaction->ip_address = $paymentDetails['data']['ip_address'];
        $creditTransaction->authorization_code = $paymentDetails['data']['authorization']['authorization_code'];
        $creditTransaction->card_last_four_digits = $paymentDetails['data']['authorization']['last4'];
        $creditTransaction->card_expiry_month = $paymentDetails['data']['authorization']['exp_month'];
        $creditTransaction->card_expiry_year = $paymentDetails['data']['authorization']['exp_year'];
        $creditTransaction->card_brand = $paymentDetails['data']['authorization']['brand'];
        $creditTransaction->bank = $paymentDetails['data']['authorization']['bank'];
        $creditTransaction->transaction_type = "credit";
        $creditTransaction->payment_processor = "paystack";
        //update wallet balance
        if($creditTransaction->save())
        {
            $top_up_amount = $creditTransaction->amount;
            $wallet_id =  $creditTransaction->payment_wallet_id;

           return $this->top_up_wallet( (int)$top_up_amount, PaymentWallet::findOrFail($wallet_id));
        }
        
    }
}
