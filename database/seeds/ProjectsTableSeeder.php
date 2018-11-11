<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->insert([
            'project_name' => '20HP Submersible',
            'client_id' => '1',
            'user_id' => '2',
            'deadline' => '2018-11-20',
            'description' => 'Lifting water from 30m below surface from river.',
            'allocation' => '0'
        ]);
    }
}
