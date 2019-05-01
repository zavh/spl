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
        DB::table('configurations')->insert([
            'name'=> 'taxconfig',
            'data'=>'{"slabs": [{"percval": "10", "slabval": "400000"}, {"percval": "15", "slabval": "500000"}, {"percval": "20", "slabval": "600000"}, {"percval": "25", "slabval": "3000000"}, {"percval": "30", "slabval": "Rest"}], "fsdata": {"male": {"age": ["65", "64"], "slab": {"64": "250000", "65": "300000"}}, "female": {"age": ["any"], "slab": {"any": "300000"}}, "disabled": {"age": ["any"], "slab": {"any": "400000"}}, "freedom_fighter": {"age": ["any"], "slab": {"any": "450000"}}}, "categories": {"male": "Male", "female": "Female", "disabled": "Disabled", "freedom_fighter": "Freedom Fighter"}}'
        ]);
        DB::table('configurations')->insert([
            'name'=> 'headconfig',
            'data'=>
            '{
                "basic":{"presentation":"Basic","taxable":true,"tax_exemption":"0","pcalc":"addition","gcalc":"addition","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":true},
                "house_rent":{"presentation":"House Rent","taxable":true,"tax_exemption":"300000","pcalc":"addition","gcalc":"addition","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":false},
                "medical_allowance":{"presentation":"Medical Allowance","taxable":true,"tax_exemption":"120000","pcalc":"addition","gcalc":"addition","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":false},
                "conveyance":{"presentation":"Conveyance","taxable":true,"tax_exemption":"30000","pcalc":"addition","gcalc":"addition","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":false},
                "pf_company":{"presentation":"PF Company","taxable":true,"tax_exemption":0,"pcalc":"deduction","gcalc":"addition","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":false},
                "pf_self":{"presentation":"PF Self","taxable":false,"tax_exemption":0,"pcalc":"deduction","gcalc":"none","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":false},
                "bonus":{"presentation":"Bonus","taxable":true,"tax_exemption":0,"pcalc":"addition","gcalc":"addition","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":true},
                "extra":{"presentation":"Extra","taxable":true,"tax_exemption":0,"pcalc":"addition","gcalc":"addition","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":true},
                "less":{"presentation":"Less","taxable":true,"tax_exemption":0,"pcalc":"deduction","gcalc":"deduction","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":true},
                "fooding":{"presentation":"Fooding","taxable":false,"tax_exemption":0,"pcalc":"deduction","gcalc":"none","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":true},
                "loan":{"presentation":"Loan","taxable":false,"tax_exemption":0,"pcalc":"deduction","gcalc":"none","valuetype":["From Profile","Percentage of Basic","Fixed Value","Upload","Function"],"default_valuetype":-1,"profile_field":"","percentage":"10","fixed_value":"0","threshold":0,"fnname":"","uploadable":false}}'


        ]);
    }
}
