<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('condition')->nullable();
            $table->decimal('price', 12, 4)->default(0);
            $table->text('description')->nullable();

            $table->boolean('is_approved')->nullable();
            $table->boolean('is_owner')->default(0);
            $table->integer('parent_id')->unsigned()->nullable();

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id', 'b2b_mp_suppliers_products_unique_id_foreign')
            ->references('id')->on('b2b_marketplace_suppliers')->onDelete('cascade');

            $table->unique(['supplier_id', 'product_id'], 'b2b_mp_products_supplier_id_product_id_unique');

            $table->integer('quote_product_id')->unsigned()->nullable();

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
        Schema::dropIfExists('b2b_marketplace_products');
    }
}
