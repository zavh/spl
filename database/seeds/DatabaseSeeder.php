<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DepartmentTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(DesignationTableSeeder::class);
        $this->call(AppDefaultConfigTableSeeder::class);
        $this->call(SalarystructuresTableSeeder::class);
    }
}
