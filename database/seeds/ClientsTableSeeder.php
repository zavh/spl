<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            'organization' => 'Megatek Engineering Pte Ltd',
            'address' => '8 Jasimuddin Avenue, Sector-3, Uttara, Dhaka-1230',
            'background' => 'Megatek is a supplier company of generators,boilers and installation works to energy sector.'
        ]);
    }
}
