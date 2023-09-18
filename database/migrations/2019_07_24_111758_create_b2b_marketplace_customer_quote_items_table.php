<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceCustomerQuoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_customer_quote_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity')->nullable();
            $table->integer('sample_unit')->nullable();
            $table->integer('shipping_time')->nunllable();

            $table->string('description')->nullable();
            $table->string('product_name')->nullable();
            $table->string('sample_image')->nullable();
            $table->string('status')->nullable();
            $table->string('quote_status')->nullable();
            $table->string('note')->nullable();
            
            $table->json('categories')->nullable();

            $table->decimal('price_per_quantity', 12, 4)->default(0)->unsigned();
            $table->decimal('sample_price', 12, 4)->default(0)->nullable();

            $table->boolean('is_requested_quote')->default(0);
            $table->boolean('is_sample_price')->default(0);
            $table->boolean('is_sample')->default(0);
            $table->boolean('is_approve')->default(0);

            $table->integer('product_id')->unsigned();
            $table->integer('quote_id')->unsigned()->nullable();  // request_for_quote_id
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('b2b_marketplace_customer_quotes')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('b2b_marketplace_suppliers')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');


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
        Schema::dropIfExists('b2b_marketplace_customer_quote_items');
    }
}
