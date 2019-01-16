<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('@lpha7SPL1'),
            'role_id' => '1',
            'active' => '1',
            'department_id' => '1',
        ]);

        DB::table('users')->insert([
            'name' => 'mahfuz',
            'email' => 'mahfuz@spl.sigma-bd.com',
            'sname' => 'Rahaman',
            'fname' => 'Mahfuzur',
            'phone' => '8801713041891',
            'address' => 'Road-16, Flat-2B, Adabor, Mohammadpur, Dhaka',
            'password' => bcrypt('@lpha7SPL1'),
            'role_id' => '1',
            'active' => '1',
        ]);

        DB::table('users')->insert([
            'name' => 'mustafiz',
            'email' => 'mustafiz@spl.sigma-bd.com',
            'sname' => 'Rahman',
            'fname' => 'Mustafizur',
            'phone' => '8801713042021',
            'address' => 'House-5, Road-16, Sector-9, Uttara, Dhaka',
            'password' => bcrypt('@lpha7SPL1'),
            'role_id' => '2',
            'active' => '1',
        ]);
    }
}
