<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellerTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_transaction_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reseller_transaction_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('varian_color')->nullable();
            $table->decimal('varian_weight')->nullable();
            $table->decimal('varian_size')->nullable();
            $table->string('varian_type')->nullable();
            $table->string('varian_taste')->nullable();
            $table->decimal('product_price')->nullable();
            $table->integer('amount_order')->default(1);
            $table->timestamps();
        });

        Schema::table('reseller_transaction_details', function (Blueprint $table) {
            $table->foreign('reseller_transaction_id')->references('id')->on('reseller_transactions');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reseller_transaction_details');
    }
}
