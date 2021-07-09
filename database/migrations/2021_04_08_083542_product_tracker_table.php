<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_tracker', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50);
            $table->unsignedBigInteger('product_quantity_id')->nullable();
            $table->foreign('product_quantity_id')->references('id')->on('product_quantity')
                ->onUpdate('cascade')
                ->onDelete('cascade');;
            $table->string('reason', 100);
            $table->enum('transaction', ['in', 'out']);
            $table->integer('previous_quantity');
            $table->integer('after_quantity');
            $table->enum('reverted', ['yes', 'no'])->default('no');
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
        Schema::dropDatabaseIfExists('product_tracker');
    }
}
