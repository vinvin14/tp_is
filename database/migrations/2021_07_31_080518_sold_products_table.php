<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SoldProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sold_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('order_id');
            // $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('stock_id');
            $table->integer('qty');
            $table->double('discounted_amount')->nullable();
            $table->double('final_amount')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->on('transactions')->references('id');
            $table->foreign('stock_id')->on('stocks')->references('id');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sold_products');
    }
}
