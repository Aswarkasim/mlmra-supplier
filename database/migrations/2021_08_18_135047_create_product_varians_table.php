<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_varians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('color')->nullable();
            $table->string('color_total')->nullable();
            $table->string('weight')->nullable();
            $table->string('weight_total')->nullable();
            $table->string('size')->nullable();
            $table->string('size_total')->nullable();
            $table->string('type')->nullable();
            $table->string('type_total')->nullable();
            $table->string('taste')->nullable();
            $table->string('taste_total')->nullable();
            $table->string('account_type')->nullable();
            $table->timestamps();
        });


        Schema::table('product_varians', function (Blueprint $table) {
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
        Schema::dropIfExists('product_varians');
    }
}
