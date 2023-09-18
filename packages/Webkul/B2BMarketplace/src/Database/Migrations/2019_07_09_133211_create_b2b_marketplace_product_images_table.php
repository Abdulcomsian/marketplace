<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_product_images', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type')->nullable();
            $table->string('path');

            $table->integer('b2b_marketplace_product_id')->unsigned();
            $table->foreign('b2b_marketplace_product_id', 'b2b_mp_products_images_foreign')->references('id')->on('b2b_marketplace_products')->onDelete('cascade');
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
        Schema::dropIfExists('b2b_marketplace_product_images');
    }
}
