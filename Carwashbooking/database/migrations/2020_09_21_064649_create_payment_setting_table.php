<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_setting', function (Blueprint $table) {
            $table->id();
            $table->boolean('cod');
            $table->boolean('stripe');
            $table->boolean('paypal');
            $table->boolean('razorpay');
            $table->text('paypal_sendbox')->nullable();
            $table->text('paypal_production')->nullable();
            $table->text('stripe_publish_key')->nullable();
            $table->text('stripe_secret_key')->nullable();
            $table->text('razorpay_key')->nullable();
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
        Schema::dropIfExists('payment_setting');
    }
}
