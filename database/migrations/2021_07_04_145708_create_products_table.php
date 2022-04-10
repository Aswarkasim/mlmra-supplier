<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('brand_name')->nullable();
            $table->integer('stock');
            $table->bigInteger('reseller_price');
            $table->bigInteger('customer_price');
            $table->bigInteger('catalog_price');
            $table->bigInteger('discount')->nullable();
            $table->boolean('isCommisionRupiah')->nullable();
            $table->bigInteger('commision_rp')->nullable();
            $table->integer('commision_persen')->nullable();
            $table->integer('point')->nullable();
            $table->integer('point_product')->nullable();
            $table->text('description');
            $table->string('media_code')->unique()->nullable();
            $table->string('status');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
