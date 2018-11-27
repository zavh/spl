<?php

use Illuminate\Database\Seeder;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('designations')->insert([
            'name' => 'General Manager',
        ]);
        DB::table('designations')->insert([
            'name' => 'Assistant General Manager',
        ]);
        DB::table('designations')->insert([
            'name' => 'Manager',
        ]);
        DB::table('designations')->insert([
            'name' => 'Assistant Manager',
        ]);
        DB::table('designations')->insert([
            'name' => 'Deputy Manager',
        ]);
        DB::table('designations')->insert([
            'name' => 'Sales Engineer',
        ]);
    }
}
