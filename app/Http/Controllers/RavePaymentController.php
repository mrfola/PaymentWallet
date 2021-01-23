<?php

namespace App\Http\Controllers;

use Rave;
use Illuminate\Http\Request;
use App\Models\PaymentWallet;
use App\Models\UserTransaction;
use App\Http\Controllers\PaymentInterface;

class RavePaymentController extends PaymentWalletController implements PaymentInterface
{

  /**
   * Initialize Rave payment process
   * @return void
   */
  public function redirectToGateway()
  {
    //This initializes payment and redirects to the payment gateway
    //The initialize method takes the parameter of the redirect URL
    Rave::initialize(route('callback-rave'));
  }


  /**
   * Obtain Rave callback information
   * @return void
   */
  public function handleGatewayCallback()
  {
    
    $resp = request()->resp; 
    $body = json_decode($resp, true); 
    $txRef = $body['data']['data']['txRef'];
    $paymentDetails = Rave::verifyTransaction($txRef);
    $creditTransaction = new UserTransaction();
    $creditTransaction->payment_wallet_id = $paymentDetails->data->meta[0]->metavalue;
    $creditTransaction->amount = $paymentDetails->data->amount;
    $creditTransaction->ip_address = $paymentDetails->data->ip;
    $creditTransaction->authorization_code = $paymentDetails->data->raveref;
    $creditTransaction->card_last_four_digits = $paymentDetails->data->card->last4digits;
    $creditTransaction->card_expiry_month = $paymentDetails->data->card->expirymonth;
    $creditTransaction->card_expiry_year = $paymentDetails->data->card->expiryyear;
    $creditTransaction->card_brand = $paymentDetails->data->card->type;
    $creditTransaction->transaction_type = "credit";

    //update wallet balance
    if($creditTransaction->save())
    {
        $top_up_amount = $creditTransaction->amount;
        $wallet_id =  $creditTransaction->payment_wallet_id;

       return $this->top_up_wallet( (int)$top_up_amount, PaymentWallet::findOrFail($wallet_id));
    }

  }

}
