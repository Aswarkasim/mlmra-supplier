<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellerCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('reseller_id')->nullable();
            $table->integer('order_count')->nullable();
            $table->string('varian_color')->nullable();
            $table->decimal('varian_weight')->nullable();
            $table->string('varian_size')->nullable();
            $table->string('varian_type')->nullable();
            $table->string('varian_taste')->nullable();
            $table->boolean('checkout')->default(false);
            $table->timestamps();
        });

        Schema::table('reseller_carts', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('reseller_id')->references('id')->on('resellers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reseller_carts');
    }
}
