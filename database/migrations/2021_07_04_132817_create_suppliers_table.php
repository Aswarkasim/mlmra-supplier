<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email');
            $table->string('whatsapp');
            $table->string('password');
            $table->boolean('isAdmin');
            $table->string('status');
            $table->unsignedBigInteger('media_id')->nullable();
            $table->timestamps();
        });

        Schema::table('suppliers', function (Blueprint $table) {
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
        Schema::dropIfExists('suppliers');
    }
}
