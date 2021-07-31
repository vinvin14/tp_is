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
            $table->foreign('category_id')->references('id')->on('product_category');
            $table->double('price');
            $table->integer('unit');
//            $table->integer('current_quantity');
            $table->integer('alert_level');
            $table->integer('priority_level')->default(0);
            $table->double('points')->default(0);
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
        Schema::dropDatabaseIfExists('product_specifications');
    }
}
