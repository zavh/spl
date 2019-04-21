<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Loan;

trait SalaryGenerator {

    private function yearly_generator($user, $fromYear, $toYear){
        
        $structure = json_decode($user->salarystructure->structure);
        $profile = json_decode($user->salary->salaryinfo);
        $profile->id = $user->id;
        $profile->employee_id = $user->name;
        $profile->name = $user->fname." ".$user->sname;

        $response['structure'] = $structure;
        $response['profile'] = $profile;
        $loans = Loan::active()->where('salary_id', $user->salary->id)->get();

        $from = Carbon::parse($fromYear);
        $to = Carbon::parse($toYear);
        $join_date = Carbon::parse($profile->join_date);

        /****** The Great Do While Loop Starts ******/
        $count = 0;
        do{
            $basic = $profile->basic;
            $fraction = $this->fraction_generator($profile, $from->copy(), $join_date->copy());
            $response['salary'][$count]['fraction'] = $fraction;
            $response['salary'][$count]['month'] = $from->copy()->toDateString();
            $response['salary'][$count]['basic'] = (float)$basic;
            
            for($i=0;$i<count($structure);$i++){
                $response['salary'][$count][$structure[$i]->param_name] = $basic * ( $structure[$i]->value / 100 )* $fraction;
            }
            if(count($loans) > 0)
                $response['salary'][$count]['loan'] = $this->loan_manager($loans, $from->copy());
            $count++;
            $from = $from->addMonth();
        }while($from < $to);
        /****** The Great Do While Loop Ends ******/
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

    private function loan_manager($loans, $target_month){
        $target_month = $target_month->endOfMonth();
        $loanarr = array();
        $loansum = 0;
        for($i=0;$i<count($loans);$i++){
            $start =  Carbon::parse($loans[$i]->start_date);
            $expiry = Carbon::parse($loans[$i]->end_date);
            if($start->gt($target_month)) continue;
            if($expiry->gt($target_month->copy()->startOfMonth()) && $expiry->lt($target_month->copy()->endOfMonth()))
                $inBetween = true;
            else $inBetween = false;
            if($expiry->gte($target_month) || $inBetween){
                $loanarr[count($loanarr)] = $loans[$i];
                $loansum += $loans[$i]->amount/$loans[$i]->tenure;
            }
        }
        $userloans['sum'] = $loansum;
        $userloans['loans'] = $loanarr;
        return $userloans;
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
        
        $firstSlab['female'] = array();
        $firstSlab['female']['age'] = 'any';
        $firstSlab['female']['slab'][$firstSlab['female']['age']] = 300000;
        $firstSlab['male'] = array();
        $firstSlab['male']['age'] = 65;
        $firstSlab['male']['slab'][$firstSlab['male']['age']-1] = 250000;
        $firstSlab['male']['slab'][$firstSlab['male']['age']] = 300000;

        $a = 0;
        if(isset($firstSlab[$gender]['slab']['any']))
            $a = $firstSlab[$gender]['slab']['any'];
        else {
            foreach($firstSlab[$gender]['slab'] as $slabage=>$value){
                if($age <= $slabage){
                    $a = $firstSlab[$gender]['slab'][$slabage];
                    break;
                }
            }
        }

        $taxableIncome = $TaxableSalary;
        $slab = array($a, 400000, 500000, 600000, 3000000);
        $slabperc = array(0,10,15,20,25,30);
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
                if($key == 'basic')
                    $cysd[$key] += $ysd['salary'][$i][$key] * $ysd['salary'][$i]['fraction'];
                else $cysd[$key] += $ysd['salary'][$i][$key];
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