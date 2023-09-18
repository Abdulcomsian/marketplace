<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_suppliers', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('channel_id')->unsigned();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('restrict');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('url')->unique();
            $table->string('company_name')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->enum('gender', ['Male', 'Female']);
            $table->date('date_of_birth')->nullable();
            $table->text('notes')->nullable();

            $table->boolean('subscribed_to_news_letter')->default(0);
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_verified')->default(0);

            $table->integer('role_id')->unsigned();

            $table->string('token')->nullable();
            $table->rememberToken();

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
        Schema::dropIfExists('b2b_marketplace_suppliers');
    }
}
