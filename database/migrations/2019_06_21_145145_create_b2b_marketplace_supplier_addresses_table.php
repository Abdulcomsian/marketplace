<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateB2bMarketplaceSupplierAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b2b_marketplace_supplier_addresses', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('b2b_marketplace_suppliers')->onDelete('cascade');

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('phone')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode')->nullable();
            $table->boolean('default_address')->default(0);

            $table->string('team_size')->nullable();
            $table->string('designation')->nullable();
            $table->string('response_time')->nullable();
            $table->string('corporate_address1')->nullable();
            $table->string('corporate_address2')->nullable();
            $table->string('corporate_phone')->nullable();
            $table->string('corporate_state')->nullable();
            $table->string('corporate_city')->nullable();
            $table->string('corporate_country')->nullable();
            $table->string('corporate_postcode')->nullable();

            $table->text('description');
            $table->string('banner')->nullable();
            $table->string('logo')->nullable();
            $table->string('tax_vat')->nullable();
            $table->string('url')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_tag_line')->nullable();
            $table->string('registerd_in')->nullable();
            $table->string('certification')->nullable();
            $table->text('company_overview')->nullable();


            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->text('return_policy')->nullable();
            $table->text('shipping_policy')->nullable();
            $table->text('privacy_policy')->nullable();

            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('skype')->nullable();
            $table->string('linked_in')->nullable();
            $table->string('pinterest')->nullable();

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
        Schema::dropIfExists('b2b_marketplace_supplier_addresses');
    }
}
