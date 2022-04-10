<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellerCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('time_applied');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('reseller_id')->nullable();
            $table->timestamps();
        });

        Schema::table('reseller_coupons', function (Blueprint $table) {
            $table->foreign('coupon_id')->references('id')->on('coupons');
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
        Schema::dropIfExists('reseller_coupons');
    }
}
