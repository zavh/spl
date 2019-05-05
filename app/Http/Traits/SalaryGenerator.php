<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Loan;
use App\Configuration;

trait SalaryGenerator {

    private function yearly_generator($user, $fromYear, $toYear){
        
        $structure = json_decode($user->salarystructure->structure, true);
        $profile = json_decode($user->salary->salaryinfo);
        $profile->id = $user->id;
        $profile->employee_id = $user->name;
        $profile->name = $user->fname." ".$user->sname;
        $response = array();

        $from = Carbon::parse($fromYear);
        $to = Carbon::parse($toYear);
        $join_date = Carbon::parse($profile->join_date);

        /****** The Great Do While Loop Starts ******/
        $count = 0;
        do{
            $fraction = $this->fraction_generator($profile, $from->copy(), $join_date->copy());
            $response['salary'][$count] = $this->structure_calculator(['structure'=>$structure, 'profile'=>$profile, 'fraction'=>$fraction, 'salary_id'=>$user->salary->id, 'from'=>$from]);
            $response['salary'][$count]['fraction'] = $fraction;
            $response['salary'][$count]['month'] = $from->copy()->toDateString();
            
            $count++;
            $from = $from->addMonth();
        }while($from < $to);
        /****** The Great Do While Loop Ends ******/
        $response['structure'] = $structure;
        $response['profile'] = $profile;
        return $response;
    }

    private function structure_calculator($c){
        $structure = $c['structure'];
        $profile = $c['profile'];
        $fraction = $c['fraction'];
        $salary_id = $c['salary_id'];
        $from = $c['from'];
        $response = array();
        foreach($structure as $key=>$value){
            if($structure[$key]['default_valuetype'] == 0){
                $response[$key] = $profile->{$structure[$key]['profile_field']} * $fraction;
            }
            if($structure[$key]['default_valuetype'] == 1){
                $response[$key] = $profile->basic * $structure[$key]['percentage']/100 * $fraction;
                if($structure[$key]['threshold'] > 0 && $response[$key] > $structure[$key]['threshold'])
                $response[$key] = $structure[$key]['threshold'];
            }
            if($structure[$key]['default_valuetype'] == 2)
                $response[$key] = $structure[$key]['fixed_value'] * $fraction;
            if($structure[$key]['default_valuetype'] == 3)
                $response[$key] = 0;
            if($structure[$key]['default_valuetype'] == 4)
                $response[$key] = $this->{$structure[$key]['fnname']}($salary_id, $from->copy());
        }

        return $response;
    }
    private function fraction_generator($profile, $from, $join_date){
        $daysInMonth = $from->daysInMonth;
        $deduction = 0;
        if($join_date->copy()->gte($from->copy()->startOfMonth()) && $join_date->copy()->lte($from->copy()->endOfMonth())){
            $deduction = $join_date->day - 1;
        }
        else if($join_date->copy()->gt($from))
            return 0;

        if($profile->end_date != null ){
            $end_date = Carbon::parse($profile->end_date);
            if($end_date->copy()->gte($from->copy()->startOfMonth()) && $end_date->copy()->lte($from->copy()->endOfMonth())){
                $deduction += ($daysInMonth - $end_date->day);
            }
            else if($end_date->copy()->lt($from))
                return 0;
        }
        return ($daysInMonth - $deduction)/$daysInMonth;
    }
    
    private function loan_manager($id, $target_month){
        $loans = Loan::active()->where('salary_id', $id)->get();
        $loansum = 0;
        if(count($loans)>0){
            $target = $target_month->format('Y-m');
            for($i=0;$i<count($loans);$i++){
                $sc = json_decode($loans[$i]->schedule);
                if(isset($sc->$target))
                    $loansum += $sc->$target;
            }
        }
        return $loansum;
    }

    private function generate_monthly_tax($s, $yt){ //$s = salary array $yt = yearly tax
        $tax_remaining = $yt;
        for($i = 0; $i<count($s['salary']);$i++){
            $tax = $s['salary'][$i]['fraction']*$tax_remaining/(12-$i);
            $s['salary'][$i]['monthly_tax'] = $tax;
            $tax_remaining -= $tax;
        }
        return $s;
    }

    private function TDS($TaxableSalary,$gender,$age)
    {
        $config = Configuration::where('name','taxconfig')->first();
        $data = json_decode($config->data, true);
        $firstSlab = $data['fsdata'];

        ################# Determining First Slab from Configuration #################
        $a = 0;
        if(isset($firstSlab[$gender]['slab']['any']))
            $a = $firstSlab[$gender]['slab']['any'];
        else {
            $ages = $firstSlab[$gender]['age'];
            asort($ages);
            foreach($ages as $i=>$confage){
                if($age <= intval($confage)){
                    $a = $firstSlab[$gender]['slab'][$firstSlab[$gender]['age'][$i]];
                    break;
                }
                $a = $firstSlab[$gender]['slab'][$firstSlab[$gender]['age'][$i]];
            }
        }
        #############################################################################

        ################ Determining Slab Values and Slab Percentages ###############
        $slabs = $data['slabs'];
        for($i=0;$i<count($slabs);$i++){
            $slab[$i] = floatval($slabs[$i]['slabval']);
            $slabperc[$i] = floatval($slabs[$i]['percval']);
        }
        array_pop($slab);
        $slab = array_merge([$a],$slab);
        $slabperc = array_merge([0], $slabperc);
        #############################################################################

        $taxableIncome = $TaxableSalary;
        $tax = array();
        $slabamount = array();

        for($i=0;$i<count($slabperc);$i++){
            if($i == (count($slabperc)-1)){
                if($taxableIncome > 0){
                    $tax[$i]=$slabperc[$i]*$taxableIncome/100;
                    $slabamount[$i] = $taxableIncome;
                }
                else {
                    $tax[$i] = 0;
                    $slabamount[$i] = 0;
                }
            }
             
            else if($i < (count($slabperc)-1)){
                if($slab[$i]<$taxableIncome) {
                    $tax[$i] = $slabperc[$i]*$slab[$i]/100;        
                    $taxableIncome -= $slab[$i];
                    $slabamount[$i] = $slab[$i];
                }
                else{
                    if($taxableIncome == 0 ){
                        $tax[$i]=0;
                        $slabamount[$i] = 0;
                    }
                    else{
                        $tax[$i]=$slabperc[$i]*$taxableIncome/100;
                        $slabamount[$i] = $taxableIncome;
                        $taxableIncome = 0;
                    }
                }
            }
        }
        $response['slabwisetax'] = $tax;
        $response['slab'] = $slab;
        $response['slabamount'] = $slabamount;
        $response['slabperc'] = $slabperc;

        return $response;
    }

    private function income_tax_calculation($ysd)
    {
        $exemptions = (Object)[
            'house_rent' => 300000,
            'conveyance' => 30000,
            'medical_allowance' => 120000,
        ];
        $investment = (Object)[
            'inv_threshold' => 30,
            'max_investment' => 15000000,
            'rebate_rate' => 15
        ];
        $min_tax = 5000;
        $tc = (Object)[
            'exemptions' => $exemptions,
            'investment' => $investment,
            'min_tax' => $min_tax,
        ];
        $cysd = ['basic' => 0,'house_rent' => 0,'conveyance' => 0,'medical_allowance' => 0,'bonus' => 0,'extra' => 0,'less' => 0,'pf_company' =>0,];
        
        $gender = $ysd['profile']->gender;
        $age = Carbon::parse($ysd['profile']->date_of_birth)->age;
        for($i=0;$i<count($ysd['salary']);$i++){
            foreach($cysd as $key=>$value){
                $cysd[$key] += $ysd['salary'][$i][$key];
            }
        }
        
        $cysd['gross_salary'] = $cysd['basic'] + $cysd['house_rent'] + $cysd['conveyance'] + $cysd['medical_allowance'] + $cysd['bonus'] + $cysd['extra'];
        $cysd['gross_total'] = $cysd['gross_salary'] + $cysd['pf_company'];
        $cysd['house_rent_exempted'] = $tc->exemptions->house_rent;
        ##### House Rent Tax Calculation with Exemption #####
        $HouseRentTR = $cysd['house_rent'];
        ($HouseRentTR-$tc->exemptions->house_rent) >= 0 ? $HouseRentTR=$HouseRentTR-$tc->exemptions->house_rent : $HouseRentTR=0;
        $cysd['house_rent_tax_remaining'] = $HouseRentTR;
        ##### Conveyance Tax Calculation with Exemption #####
        $cysd['conveyance_exempted'] = $exemptions->conveyance;

        $conveyanceTR = $cysd['conveyance'];
        if(($conveyanceTR-$exemptions->conveyance)>=0)$conveyanceTR=$conveyanceTR-$exemptions->conveyance; else $conveyanceTR=0;
        $cysd['conveyance_tax_remaining'] = $conveyanceTR;
        ##### Medical Allowance Tax Calculation with Exemption #####
        $cysd['medical_allowance_exempted'] = $exemptions->medical_allowance;

        $medicalTR = $cysd['medical_allowance'];
        if(($medicalTR-$exemptions->medical_allowance)>=0)$medicalTR=$medicalTR-$exemptions->medical_allowance; else $medicalTR=0;
        $cysd['medical_allowance_tax_remaining'] = $medicalTR;
        ##### Taxable Salary Calculation #####
        $taxableFields = $HouseRentTR + $conveyanceTR + $medicalTR + $cysd['pf_company'] + $cysd['bonus'] + $cysd['extra'] - $cysd['less'];

        $cysd['taxable_salary'] = $cysd['basic'] + $taxableFields;
        $info = $this->TDS($cysd['taxable_salary'],$gender,$age);

        $tax = $info['slabwisetax'];
        
        $cysd['slabinfo'] = $info;

        $Tax = array_sum($tax);
        $cysd['taxbeforeinv'] = $Tax;

        $TI1 = $cysd['taxable_salary'] - $cysd['pf_company'];//is bonus includedin tax investment?
        $threshold = ceil($TI1 * ($tc->investment->inv_threshold/100));
        $threshold > $tc->investment->max_investment ? $MaxInvestment = $tc->investment->max_investment : $MaxInvestment = $threshold;
        $cysd['MaxInvestment'] = $MaxInvestment;
        $TIRebate = ceil($MaxInvestment * ($tc->investment->rebate_rate/100));
        $cysd['TIRebate'] = $TIRebate;
        $finalTax = $Tax - $TIRebate;
        $finalTax > 0 && $finalTax < $tc->min_tax ? $cysd['finalTax'] = $tc->min_tax : $cysd['finalTax'] = $finalTax;
        $finalTax < 0 ? $cysd['finalTax'] = 0 : $cysd['finalTax'] = $finalTax;

        return $cysd;
    }

    private function yearly_income_table_data_entry($yearlyProbableSalary,$tablename)
    {
        
        DB::table($tablename)->insert([
            'user_id' => $yearlyProbableSalary['profile']->id,
            'name' => $yearlyProbableSalary['profile']->employee_id,
            'salary' => json_encode($yearlyProbableSalary['salary']),
            'profile' => json_encode($yearlyProbableSalary['profile']),
            'structure' => json_encode($yearlyProbableSalary['structure']),
            'tax_config' => json_encode($yearlyProbableSalary['tax_config']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}