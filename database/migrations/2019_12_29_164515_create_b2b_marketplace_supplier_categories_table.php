<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceSupplierCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_supplier_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('supplier_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->boolean('status')->default(0);

            $table->foreign('supplier_id')->references('id')->on('b2b_marketplace_suppliers')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

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
        Schema::dropIfExists('b2b_marketplace_supplier_category');
    }
}
