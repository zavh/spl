<?php

use Illuminate\Database\Seeder;

class ConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configuration')->insert([
            'name'=> 'taxconfig',
            'data'=>'{"slabs": [{"percval": "10", "slabval": "400000"}, {"percval": "15", "slabval": "500000"}, {"percval": "20", "slabval": "600000"}, {"percval": "25", "slabval": "3000000"}, {"percval": "30", "slabval": "Rest"}], "fsdata": {"male": {"age": ["65", "64"], "slab": {"64": "250000", "65": "300000"}}, "female": {"age": ["any"], "slab": {"any": "300000"}}, "disabled": {"age": ["any"], "slab": {"any": "400000"}}, "freedom_fighter": {"age": ["any"], "slab": {"any": "450000"}}}, "categories": {"male": "Male", "female": "Female", "disabled": "Disabled", "freedom_fighter": "Freedom Fighter"}}'
        ]);
    }
}
