<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned();
            $table->integer('landlord_id')->unsigned();
            $table->integer('tenant_id')->unsigned();
            $table->double('amount', 8, 2);
            $table->string('status');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('merchant_reference')->nullable();
            $table->string('psp_reference')->nullable();
            $table->string('payment_auth_result')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
