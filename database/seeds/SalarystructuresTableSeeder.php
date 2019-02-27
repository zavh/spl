<?php

use Illuminate\Database\Seeder;

class SalarystructuresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('salarystructures')->insert([
            'structurename' => 'config',
            'structure' => '[{"param_name":"house_rent","param_uf_name":"House Rent","value":0,"deleted":false},{"param_name":"conveyance","param_uf_name":"Conveyance","value":0,"deleted":false},{"param_name":"medical_allowance","param_uf_name":"Medical Allowance","value":0,"deleted":false},{"param_name":"pf_self","param_uf_name":"PF Self","value":0,"deleted":false},{"param_name":"pf_compnay","param_uf_name":"PF Company","value":0,"deleted":false},{"param_name":"bonus","param_uf_name":"Bonus","value":0,"deleted":false},{"param_name":"loan","param_uf_name":"Loan","value":0,"deleted":false},{"param_name":"hire_purchase","param_uf_name":"Hire Purchase","value":0,"deleted":false},{"param_name":"extra","param_uf_name":"Extra","value":0,"deleted":false},{"param_name":"less","param_uf_name":"Less","value":0,"deleted":false}]',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
