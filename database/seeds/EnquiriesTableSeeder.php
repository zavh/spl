<?php

use Illuminate\Database\Seeder;

class EnquiriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enquiries')->insert([
            'project_id' => '1',
            'details' => '{"type": "submerse", "liquid": "Water", "liqtemp": "32", "pumpcap": "36", "subtype": "borewell", "deadline": "2018-11-11", "pumphead": "20", "client_id": "1", "description": "Lifting water from 30m below surface from river.", "project_name": "20HP Submersible"}',
        ]);
    }
}
