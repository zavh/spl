<?php

use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            'parent_id' => 0,
            'name' => 'Sigma Pumps Limited',
            'path' => '{"0": "0"}',
        ]);
    }
}
