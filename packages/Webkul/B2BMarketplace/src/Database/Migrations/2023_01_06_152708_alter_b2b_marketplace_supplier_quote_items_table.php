<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b2b_marketplace_supplier_quote_items', function (Blueprint $table) {
            $table->dropColumn('product_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b2b_marketplace_supplier_quote_items', function (Blueprint $table) {
            $table->string('product_name', 191)->nullable(true);
        });
    }
};
