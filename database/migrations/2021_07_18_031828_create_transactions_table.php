<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('waybill_number')->nullable();
            $table->string('transaction_code')->nullable();
            $table->decimal('total_price');
            $table->string('promo_code')->nullable(); // cukup salaj satu ygg digunakan
            $table->string('kupon_id')->nullable(); // promo code atau kupon id
            $table->string('shipping_method')->nullable();
            $table->string('custom_courier_name')->nullable();
            $table->string('custom_courier_phone_number')->nullable();
            $table->string('courier_type')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('ongkir_type')->nullable(); // free, reguler, manual
            $table->string('payment_status')->nullable();
            $table->string('transaction_status')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('address_name')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->string('street')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
