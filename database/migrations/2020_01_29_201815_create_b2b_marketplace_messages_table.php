<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message');
            $table->string('role');
            $table->boolean('is_new');

            $table->integer('message_id')->unsigned();
            $table->foreign('message_id')->references('id')->on('b2b_marketplace_message_mappings')->onDelete('cascade');

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
        Schema::dropIfExists('b2b_marketplace_messages');
    }
}
