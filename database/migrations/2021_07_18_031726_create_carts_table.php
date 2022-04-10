<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_reseller_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->integer('order_count')->nullable();
            $table->string('varian_color')->nullable();
            $table->decimal('varian_weight')->nullable();
            $table->decimal('varian_size')->nullable();
            $table->string('varian_type')->nullable();
            $table->string('varian_taste')->nullable();
            $table->timestamps();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('product_reseller_id')->references('id')->on('product_resellers');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
