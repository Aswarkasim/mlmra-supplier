<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->nullable();
            $table->string('full_name');
            $table->string('phone_number');
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('job')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('password');
            $table->integer('point')->nullable();
            $table->decimal('commision_total')->nullable();
            $table->integer('product_quality_rating')->default(0)->nullable();
            $table->integer('total_product_sold')->default(0)->nullable();
            $table->integer('total_ulasan')->default(0)->nullable();
            $table->string('status');
            $table->string('level')->nullable();
            $table->string('referal_code')->unique()->nullable();
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->integer('referal_count')->nullable();
            $table->decimal('referal_bonus')->nullable();
            $table->string('api_token')->nullable();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->timestamps();
        });

        Schema::table('resellers', function (Blueprint $table) {
            $table->foreign('media_id')->references('id')->on('media');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resellers');
    }
}
