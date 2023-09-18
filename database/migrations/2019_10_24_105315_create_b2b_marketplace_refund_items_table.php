<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2BMarketplaceRefundItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_refund_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('b2b_marketplace_refund_id')->unsigned();
            $table->foreign('b2b_marketplace_refund_id')->references('id')->on('b2b_marketplace_refunds')->onDelete('cascade');

            $table->integer('refund_item_id')->unsigned();
            $table->foreign('refund_item_id')->references('id')->on('refund_items')->onDelete('cascade');

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
        Schema::dropIfExists('marketplace_refund_items');
    }
}
