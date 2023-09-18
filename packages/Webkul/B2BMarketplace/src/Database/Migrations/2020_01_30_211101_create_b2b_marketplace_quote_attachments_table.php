<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceQuoteAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_quote_attachments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type')->nullable();
            $table->string('path');

            $table->integer('customer_quote_id')->unsigned();

            $table->foreign('customer_quote_id')->references('id')->on('b2b_marketplace_customer_quotes')->onDelete('cascade');
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
        Schema::dropIfExists('b2b_marketplace_quote_attachments');
    }
}
