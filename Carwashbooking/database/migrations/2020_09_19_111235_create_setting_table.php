<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_address');
            $table->string('phone',100);
            $table->string('website');
            $table->string('currency');
            $table->string('company_logo');
            $table->string('company_favicon');
            $table->string('currency_symbol');
            $table->boolean('user_verification')->default(1);
            $table->boolean('sms_verification')->default(1);
            $table->boolean('mail_verification')->default(1);
            $table->boolean('push_notification')->default(0);
            $table->boolean('mail_notification')->default(0);
            $table->boolean('service_at_home')->default(0);
            $table->string('twilio_acc_id');
            $table->string('twilio_auth_token');
            $table->string('twilio_phone_no');
            $table->text('latitude');
            $table->text('longitude');
            $table->text('onesignal_app_id');
            $table->text('onesignal_auth_key');
            $table->text('rest_api_key');
            $table->text('project_number');
            $table->text('mail_host');
            $table->text('mail_port');
            $table->text('mail_username');
            $table->text('mail_password');
            $table->text('mail_encryption');
            $table->text('mail_from_address');
            $table->text('privacy_policy');
            $table->string('website');
            $table->text('license_code');
            $table->text('client_name');
            $table->boolean('license_verify');
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
        Schema::dropIfExists('setting');
    }
}
