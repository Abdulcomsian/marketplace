<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceQuoteMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_quote_messages', function (Blueprint $table) {
            $table->increments('id');

            $table->mediumText('message')->nullable();

            $table->integer('supplier_id')->nullable()->unsigned();
            $table->integer('customer_quote_item_id')->nullable()->unsigned();
            $table->integer('supplier_quote_item_id')->nullable()->unsigned();
            $table->integer('customer_id')->nullable()->unsigned();

            $table->foreign('supplier_id')->references('id')->on('b2b_marketplace_suppliers')->onDelete('cascade');
            $table->foreign('customer_quote_item_id')->references('id')->on('b2b_marketplace_customer_quote_items')->onDelete('cascade');
            $table->foreign('supplier_quote_item_id')->references('id')->on('b2b_marketplace_supplier_quote_items')->onDelete('cascade');
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
        Schema::dropIfExists('b2b_marketplace_quote_messages');
    }
}
