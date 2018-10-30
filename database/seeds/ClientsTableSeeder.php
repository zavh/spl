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
            'name' => 'Ahmed Rasul',
            'organization' => 'Applic Apparels',
            'address' => 'Road-16, Flat-2B, Adabor, Mohammadpur, Dhaka',
            'contact' => '8801713041891',
        ]);
    }
}
