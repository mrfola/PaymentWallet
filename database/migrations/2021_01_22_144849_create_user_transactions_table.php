<?php

use App\Models\UserTransaction;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_wallet_id');
            $table->text('amount');
            $table->text('ip_address');
            $table->text('authorization_code')->nullable();
            $table->text('card_last_four_digits')->nullable();
            $table->text('card_expiry_month')->nullable();
            $table->text('card_expiry_year')->nullable();
            $table->text('card_brand')->nullable();
            $table->text('bank')->nullable();
            $table->enum('transaction_type', ['debit', 'credit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_transactions');
    }
}
