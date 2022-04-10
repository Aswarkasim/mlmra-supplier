<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('whatsapp')->nullable();
            $table->string('password');
            $table->boolean('isAdmin');
            $table->string('status');
            $table->decimal('product_quality_rating')->default(0)->nullable();
            $table->integer('total_product_sold')->default(0)->nullable();
            $table->integer('total_ulasan')->default(0)->nullable();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
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
        Schema::dropIfExists('users');
    }
}
