<?php

namespace App\Http\Controllers;

interface PaymentInterface
{
    //money amount in the database is saved in naira
    //money amount in the form is collected in naira
    //if your payment processor requires the amount in kobo, please convert it in your controller.
    //don't forget to convert it back to naira before storing in the database
    public function redirectToGateway();
    public function handleGatewayCallback();
}
