<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierColumnToProductInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_inventories', function (Blueprint $table) {
            $table->dropForeign('product_inventories_inventory_source_id_foreign');
            $table->dropForeign('product_inventories_product_id_foreign');
            $table->dropUnique('product_source_vendor_index_unique');

            $table->integer('supplier')->nullable();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('inventory_source_id')->references('id')->on('inventory_sources')->onDelete('cascade');

            $table->unique(['product_id', 'inventory_source_id', 'vendor_id','supplier'], 'product_source_vendor_supplier_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_inventories', function (Blueprint $table) {
            $table->dropColumn('supplier');
        });
    }
}
