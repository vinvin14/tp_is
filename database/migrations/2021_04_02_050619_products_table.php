<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->longtext('description')->nullable();
            $table->string('uploaded_img', 100)->nullable();
            $table->string('original_img_file_name', 100)->nullable();
            $table->unsignedBigInteger('category_id');
            $table->double('price');
            $table->unsignedBigInteger('unit_id');
//            $table->integer('current_quantity');
            $table->integer('alert_level');
            $table->double('points')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropDatabaseIfExists('products');
    }
}
