<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $table = 'payment_setting';

    protected $fillable = ['cod','paypal','flutterwave','stripe','razorpay','paypal_production','paypal_sendbox','stripe_publish_key','paystack','paystack_public_key','flutterwave_public_key','stripe_secret_key','razorpay_key'];
}
