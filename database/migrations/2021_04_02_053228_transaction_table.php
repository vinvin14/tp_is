<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer');
            $table->foreign('customer')->references('id')->on('customers');
            $table->string('order_ticket')->unique();
            $table->date('transaction_date');
            $table->double('total_amount')->nullable();
            $table->string('total_points')->nullable();
            $table->enum('trans_status', ['pending', 'completed', 'denied'])->default('pending');
            $table->enum('claim_type', ['pick-up', 'deliver'])->nullable();
            $table->unsignedBigInteger('payment_type')->nullable();
            $table->foreign('payment_type')->references('id')->on('payment_types');
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
        Schema::dropDatabaseIfExists('transactions');
    }
}
