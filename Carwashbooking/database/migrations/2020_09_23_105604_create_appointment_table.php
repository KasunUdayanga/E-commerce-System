<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment', function (Blueprint $table) {
            $table->id();
            $table->string('appoinment_id');
            $table->integer('user_id');
            $table->integer('coworker_id');
            $table->string('service_id');
            $table->integer('coupen_id')->nullable();
            $table->string('coupen_price')->nullable(); 
            $table->string('appointment_status');
            $table->text('date');
            $table->text('start_time');
            $table->text('end_time');
            $table->text('amount');
            $table->text('payment_token');
            $table->text('payment_time');
            $table->text('address');
            $table->text('lat');
            $table->text('lang');
            $table->text('service_type');
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
        Schema::dropIfExists('appointment');
    }
}
