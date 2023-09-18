<?php

namespace Webkul\B2BMarketplace\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Webkul\User\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {

        DB::table('b2b_marketplace_supplier_roles')->delete();

        DB::table('b2b_marketplace_supplier_roles')->insert([
            'id'              => 1,
            'name'            => 'Administrator',
            'description'     => 'Administrator rolem',
            'permission_type' => 'all',
        ]);
    }
}