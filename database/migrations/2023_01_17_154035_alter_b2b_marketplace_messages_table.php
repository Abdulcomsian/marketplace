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
        Schema::table('b2b_marketplace_messages', function (Blueprint $table) {
            $table->string('msg_type')->after('message')->nullable(true);
            $table->string('extension')->after('msg_type')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b2b_marketplace_messages', function (Blueprint $table) {
            $table->dropColumn('msg_type');
            $table->dropColumn('extension');
        });
    }
};
