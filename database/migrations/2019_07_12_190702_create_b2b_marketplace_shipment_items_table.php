<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceShipmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_shipment_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('b2b_marketplace_shipment_id')->unsigned();
            $table->foreign('b2b_marketplace_shipment_id','b2b_mp_shipment_items_id')->references('id')->on('b2b_marketplace_shipments')->onDelete('cascade');

            $table->integer('b2b_shipment_item_id')->unsigned();
            $table->foreign('b2b_shipment_item_id')->references('id')->on('shipment_items')->onDelete('cascade');

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
        Schema::dropIfExists('b2b_marketplace_shipment_items');
    }
}
