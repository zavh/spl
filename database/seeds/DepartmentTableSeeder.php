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
            'path' => '{"0": "0"}',
            'name' => 'Application',
            'dirname' => 'homes',
            'apppermission' => '{"modname": "all","action": [{"name": "all","func": "all","url": "all"}]}',
        ]);
    }
}
