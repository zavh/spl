<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role_name' => 'superadmin',
            'role_description' => 'All Permissions',
        ]);
        DB::table('roles')->insert([
            'role_name' => 'admin',
            'role_description' => 'Department Admin',
        ]);
        DB::table('roles')->insert([
            'role_name' => 'user',
            'role_description' => 'Department User',
        ]);
    }
}
