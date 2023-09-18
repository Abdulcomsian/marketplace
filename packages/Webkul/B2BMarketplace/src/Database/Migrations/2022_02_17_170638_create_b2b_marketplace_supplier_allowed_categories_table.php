<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateB2bMarketplaceSupplierAllowedCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_supplier_allowed_categories', function (Blueprint $table) {
            $table->id();
            $table->json('categories')->nullable();

            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('b2b_marketplace_suppliers')->onDelete('cascade');
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
        Schema::dropIfExists('b2b_marketplace_supplier_allowed_categories');
    }
}
