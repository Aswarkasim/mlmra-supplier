<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('point');
            $table->unsignedBigInteger('reseller_transaction_id')->nullable();
            $table->unsignedBigInteger('reseller_id')->nullable();
            $table->timestamps();
        });

        Schema::table('point_histories', function (Blueprint $table) {
            $table->foreign('reseller_transaction_id')->references('id')->on('reseller_transactions');
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
        Schema::dropIfExists('point_histories');
    }
}
