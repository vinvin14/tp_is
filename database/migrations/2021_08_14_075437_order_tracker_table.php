<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_tracker', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('order_id');
            $table->integer('order_qty');
            $table->unsignedBigInteger('stock_id');
            $table->integer('stock_previous_qty');
            $table->integer('stock_after_qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_tracker');
    }
}
