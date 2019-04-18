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
            $expiry = Carbon::parse($loans[$i]->end_date);
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
        
        $firstSlab['f'] = array();
        $firstSlab['f']['age'] = 'any';
        $firstSlab['f']['slab'][$firstSlab['f']['age']] = 300000;
        $firstSlab['m'] = array();
        $firstSlab['m']['age'] = 65;
        $firstSlab['m']['slab'][$firstSlab['m']['age']-1] = 250000;
        $firstSlab['m']['slab'][$firstSlab['m']['age']] = 300000;

        $a = 0;
        if($firstSlab[$gender]['age'] == 'any')
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
        $cysd['basicSalary'] = 0;
        $cysd['houseRent'] = 0;
        $cysd['conveyance'] = 0;
        $cysd['medicalAllowance'] = 0;
        $cysd['bonus'] = 0;
        $cysd['extra'] = 0;
        $cysd['less'] = 0;
        $cysd['medicalAllowance'] = 0;
        $cysd['pfCompany'] = 0;
        
        $gender = $ysd['profile']->gender;
        $age = Carbon::parse($ysd['profile']->date_of_birth)->age;
        for($i=0;$i<count($ysd['salary']);$i++){
            $cysd['basicSalary'] += $ysd['salary'][$i]['basic'] * $ysd['salary'][$i]['fraction'];
            $cysd['houseRent'] += $ysd['salary'][$i]['house_rent'];
            $cysd['conveyance'] += $ysd['salary'][$i]['conveyance'];
            $cysd['medicalAllowance'] += $ysd['salary'][$i]['medical_allowance'];
            $cysd['pfCompany'] += $ysd['salary'][$i]['pf_company'];
            $cysd['bonus'] += $ysd['salary'][$i]['bonus'];
            $cysd['extra'] += $ysd['salary'][$i]['extra'];
            $cysd['less'] += $ysd['salary'][$i]['less'];
        }

        $response['basic'] = $cysd['basicSalary'];
        $response['house_rent'] = $cysd['houseRent'];
        $response['conveyance'] = $cysd['conveyance'];
        $response['medical_allowance'] = $cysd['medicalAllowance'];
        $response['bonus'] = $cysd['bonus'];
        $response['extra'] = $cysd['extra'];
        $response['less'] = $cysd['less'];
        

        $grossSalary = $cysd['basicSalary'] + $cysd['houseRent'] + $cysd['conveyance'] + $cysd['medicalAllowance'] + $cysd['bonus'] + $cysd['extra'];
        $response['gross_salary'] = $grossSalary;

        $grossTotal = $grossSalary +$cysd['pfCompany'];
        $response['gross_total'] = $grossTotal;

        $houseRentExempted = 300000;
        $response['house_rent_exempted'] = $houseRentExempted;

        $HouseRentTR = $cysd['houseRent'];
        if(($HouseRentTR-$houseRentExempted)>=0)$HouseRentTR=$HouseRentTR-$houseRentExempted; else $HouseRentTR=0;
        $response['house_rent_tax_remaining'] = $HouseRentTR;
        
        $conveyanceExempted = 30000;
        $response['conveyance_exempted'] = $conveyanceExempted;

        $conveyanceTR = $cysd['conveyance'];
        if(($conveyanceTR-$conveyanceExempted)>=0)$conveyanceTR=$conveyanceTR-$conveyanceExempted; else $conveyanceTR=0;
        $response['conveyance_tax_remaining'] = $conveyanceTR;

        $medicalExempted = 120000;
        $response['medical_allowance_exempted'] = $medicalExempted;

        $medicalTR = $cysd['medicalAllowance'];
        if(($medicalTR-$medicalExempted)>=0)$medicalTR=$medicalTR-$medicalExempted; else $medicalTR=0;
        $response['medical_allowance_tax_remaining'] = $medicalTR;

        $response['pf_company'] = $cysd['pfCompany'];

        $taxableFields = $HouseRentTR + $conveyanceTR + $medicalTR + $cysd['pfCompany'] + $cysd['bonus'] + $cysd['extra'] - $cysd['less'];

        $TaxableSalary = $cysd['basicSalary'] + $taxableFields;
        $response['taxable_salary'] = $TaxableSalary;

        $info = $this->TDS($TaxableSalary,$gender,$age);

        $tax = $info['slabwisetax'];
        
        $response['slabinfo'] = $info;

        $Tax = array_sum($tax);
        $response['taxbeforeinv'] = $Tax;

        // if($Tax > 5000)
        // {
            $TI1 = $TaxableSalary - $cysd['pfCompany'];//is bonus includedin tax investment?
            // $MaxInvestment = ceil($TI1 * (30/100));
            ceil($TI1 * (30/100)) > 15000000 ? $MaxInvestment = 15000000 : $MaxInvestment = ceil($TI1 * (30/100));
            $response['MaxInvestment'] = $MaxInvestment;
            $TIRebate = ceil($MaxInvestment * (15/100));
            $response['TIRebate'] = $TIRebate;
            $finalTax = $Tax - $TIRebate;
            $finalTax > 0 && $finalTax < 5000 ? $response['finalTax'] = 5000 : $response['finalTax'] = $finalTax;
            $finalTax < 0 ? $response['finalTax'] = 0 : $response['finalTax'] = $finalTax;
        // }
        // else if($Tax>0 && $Tax<=5000) 
        // {
        //     $finalTax = 5000;
        //     $response['finalTax'] = $finalTax;
        // }
        // else
        // {
        //     $response['MaxInvestment'] = 0;
        //     $response['TIRebate'] = 0;
        //     $response['finalTax'] = 0;
        // }
        return $response;
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