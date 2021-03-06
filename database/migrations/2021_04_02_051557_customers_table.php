<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 50);
            $table->string('middlename', 50);
            $table->string('lastname', 50);
            $table->date('date_of_birth')->nullable();
            $table->string('address', 100)->nullable();
            $table->double('total_points')->nullable()->default(0);
            $table->double('total_purchase_amount')->nullable()->default(0);
            $table->unsignedBigInteger('last_purchase')->nullable();
            $table->enum('customer_type', ['guest', 'member', 'regular']);
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
        Schema::dropDatabaseIfExists('customers');
    }
}
