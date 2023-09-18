<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_invoice_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('b2b_marketplace_invoice_id')->unsigned();
            $table->foreign('b2b_marketplace_invoice_id')->references('id')->on('b2b_marketplace_invoices')->onDelete('cascade');

            $table->integer('invoice_item_id')->unsigned();
            $table->foreign('invoice_item_id')->references('id')->on('invoice_items')->onDelete('cascade');

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
        Schema::dropIfExists('b2b_marketplace_invoice_items');
    }
}
