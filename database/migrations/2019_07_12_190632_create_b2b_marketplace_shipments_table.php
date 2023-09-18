<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_shipments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('total_qty')->nullable();

            $table->integer('shipment_id')->unsigned();
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');

            $table->integer('b2b_marketplace_order_id')->unsigned();
            $table->foreign('b2b_marketplace_order_id')->references('id')->on('b2b_marketplace_orders')->onDelete('cascade');

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
        Schema::dropIfExists('b2b_marketplace_shipments');
    }
}
