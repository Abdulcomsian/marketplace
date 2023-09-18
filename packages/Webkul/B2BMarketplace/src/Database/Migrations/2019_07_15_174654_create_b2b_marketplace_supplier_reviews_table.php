<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceSupplierReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void 
     */
    public function up()
    {
        Schema::create('b2b_marketplace_supplier_reviews', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->string('status');

            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id', 'b2b_mp_reviews_supplier_id_foreign')->references('id')->on('b2b_marketplace_suppliers')->onDelete('cascade');

            $table->integer('customer_id')->unsigned();
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
        Schema::dropIfExists('b2b_marketplace_supplier_reviews');
    }
}
