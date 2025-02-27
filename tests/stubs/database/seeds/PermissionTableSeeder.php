<?php

namespace GenesisTecnologia\Defender\Testing;

use GenesisTecnologia\Defender\Permission;
use Illuminate\Database\Seeder;

/**
 * Class PermissionTableSeeder.
 */
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run()
    {
        Permission::unguard();

        Permission::create(['name' => 'user.create', 'readable_name' => '']);
        Permission::create(['name' => 'user.delete', 'readable_name' => '']);
    }
}
