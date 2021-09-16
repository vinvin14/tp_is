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
            $table->unsignedBigInteger('customer')->nullable();
            $table->string('ticket_number')->unique()->nullable();
            $table->date('transaction_date');
            $table->double('total_amount')->nullable();
            $table->string('total_points')->nullable();
            $table->enum('trans_status', ['pending', 'completed', 'denied'])->default('pending');
            $table->unsignedBigInteger('claim_type')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('isWalkin')->default(0);
            $table->timestamps();


            $table->foreign('customer')->references('id')->on('customers');
            $table->foreign('payment_method_id')->references('id')->on('payment_method');
            $table->foreign('claim_type')->references('id')->on('claim_type');
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
