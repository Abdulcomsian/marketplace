<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_transactions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type')->nullable();
            $table->string('transaction_id')->unique();
            $table->string('method')->nullable();
            $table->text('comment')->nullable();

            $table->decimal('base_total', 12, 4)->default(0)->nullable();

            $table->integer('supplier_id')->unsigned();
            $table->integer('b2b_marketplace_order_id')->unsigned();

            $table->foreign('supplier_id')->references('id')->on('b2b_marketplace_suppliers')->onDelete('cascade');
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
        Schema::dropIfExists('b2b_marketplace_transactions');
    }
}
