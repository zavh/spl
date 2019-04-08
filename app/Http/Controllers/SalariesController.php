<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Salary;
use App\SalaryStructure;
use App\Loan;
use Carbon\Carbon;
// use App\SalaryStructure;
use App\User;
use Illuminate\Support\Facades\Storage;

class SalariesController extends Controller
{
    private $taxable_income = 0;
    private $yearly_tds = 0;

    public function index()
    {
        return view('salaries.query');        
    }

    private function salaryupdate_generator($lastmonth)
    {
        $salaryprofile_updated = SalaryStructure::all();
        Schema::connection('mysql')->create('salaryupdate_'.$lastmonth, function($table)
        {
            $table->increments('id');
            $table->integer('structureid')->nullable()->default(0);
            $table->string('name')->nullable()->default('');
            $table->dateTime('update')->nullable();
            $table->timestamps();
        });
        foreach($salaryprofile_updated as $spupdate)
        {
            DB::table('salaryupdate_'.$lastmonth)->insert([
                'structureid' => $spupdate->id,
                'name' => $spupdate->structurename,
                'update' => $spupdate->updated_at
            ]);
        } 
    }
    private function salary_updater($lastmonth)
    {
        DB::table('salaryupdate_'.$lastmonth)->truncate(); //truncate table

        $refreshprofiles = SalaryStructure::all();
        $refreshtable = array();
        $x = 0;
        foreach($refreshprofiles as $spupdate)  //reinsert updated data
        {
            $refreshtable[$x]['structureid'] =  $spupdate->id;
            $refreshtable[$x]['name'] = $spupdate->structurename;
            $refreshtable[$x]['update'] = $spupdate->updated_at;
            DB::table('salaryupdate_'.$lastmonth)->insert([
                'structureid' => $refreshtable[$x]['structureid'],
                'name' => $refreshtable[$x]['name'],
                'update' => $refreshtable[$x]['update']
            ]);
        }
    }
    private function salary_generator($lastmonth)
    {
        Schema::connection('mysql')->create('salary_'.$lastmonth, function($table)
        {
            $table->increments('id');
            $table->integer('user_id')->nullable()->default(0);
            $table->json('salarydata')->nullable();
            $table->float('fraction',3,2)->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    private function store_salary($salary,$lastmonth)
    {
        $salarydata = array();
        $r = array();
        $taxable_income = array();
        $monthly_taxable_income = array();
        $tax_paid = array();
        $i = 0;
        
        $lastmonth_month = Carbon::now()->subMonth()->month; //last month
        $lastmonth_year = Carbon::now()->subMonth()->year;

        $_month = Carbon::now()->subMonth()->subMonth()->month;//last to last month
        $_year = Carbon::now()->subMonth()->subMonth()->year;
        $_month_year = $_month."_".$_year;
        $lastmonth_start = Carbon::now()->subMonth()->startOfMonth();
        // dd($salary);

        foreach ($salary as $sal) 
        {   
            // $si = (object)$sal;

            $joindate = $sal['join_date'];
            $joindate_m = Carbon::parse($joindate)->month;
            $lastmonth_m = Carbon::parse($lastmonth_start)->month;
            
            $joindate_y = Carbon::parse($joindate)->year;
            $lastmonth_y = Carbon::parse($lastmonth_start)->year;

            $joindate_d = Carbon::parse($joindate)->day;
            $lastmonth_d = Carbon::parse($lastmonth_start)->day;

            

            if($joindate_y == $lastmonth_y && $joindate_m == $lastmonth_m && $joindate_d > $lastmonth_d)//joined after previous month starts
            {
                $fraction[$i] = (float)($joindate_d/$lastmonth_start->daysInMonth);
            }
            else if($joindate_y > $lastmonth_y || ($joindate_y == $lastmonth_y && $joindate_m > $lastmonth_m) )
            {
                continue; //future date
            }
            else
            {
                $fraction[$i] = 1;
            }
            // dd($d,$joindate_y,$joindate_m,$joindate_d,$lastmonth_y,$lastmonth_m,$lastmonth_d);

            $userinfo[$i] = User::where('name','=',$sal['name'])->get()->all();
            $id[$i] = $userinfo[$i][0]->id;
            $salarydata[$i] = json_encode($sal);

            DB::table('salary_'.$lastmonth)->insert([
                'user_id' => $id[$i],
                'salarydata' => $salarydata[$i],
                'fraction'=> $fraction[$i]
            ]);
            $i++;
        }
        // dd($fraction);
    }

    public function dbcheck($year = null){
        if($year == null){
            $today = date("Y-m-d");
            if($today > date("Y-07-31")){
                $fromYear = date("Y") + 0;
                $toYear = date("Y") + 1;
            }
            else{
                $fromYear = date("Y") - 1;
                $toYear = date("Y") + 0;
            }
        }
        else {
            $fromYear = $year + 0;
            $toYear = $year + 1;
        }
        if(Schema::hasTable('salary_'.$fromYear."_".$toYear)){
            $response['status'] = 'success';
            $response['fromYear'] = $fromYear;
            $response['toYear'] = $toYear;
        }
        else {
            $response['all'] = $this->initial_salary_generator("$fromYear-07-01", "$toYear-06-30");
        }
        
        return response()->json($response);
    }

    private function initial_salary_generator($fromYear, $toYear)
    {
        $users = User::actual()->get();
        $data = array();
        foreach($users as $index=>$user){
            $data[$index] = $this->yearly_generator($user, $fromYear, $toYear);
            $data[$index] = $this->income_tax_calculation($data[$index]);
        }
        return $data;
    }

    private function yearly_generator($user, $fromYear, $toYear){
        
        $structure = json_decode($user->salarystructure->structure);
        $profile = json_decode($user->salary->salaryinfo);

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

    private function tabhead_generation()
    {
        $users = User::where('active', 1)->actual()->get();
        $count = 0;
        $salary = array();
        $tabheads = array('Employee ID','Basic','Date of Joining');
        $flag = 0;
        foreach($users as $index=>$user)
        {
            $sstructure = $user->salarystructure;

            $x = $user->salary;
            $salary[$count]['name'] = $x->user->name;
            $si = json_decode($x->salaryinfo);
            $salary[$count]['basic'] = $si->basic;
            
            if(empty($sstructure)){
                $salary[$count] = null;
            }
            else {
                $ss = json_decode($sstructure->structure);
                foreach($ss as $breakdown){
                    if($flag == 0)
                        $tabheads[count($tabheads)]=$breakdown->param_uf_name;
                }
                $tabheads['gross_salary'] = "Gross Salary";
                $tabheads['gross_total'] = "Gross Total";
                $tabheads['deduction_total'] = "Deduction Total";
                $tabheads['net_salary'] = "Net Salary";
                $flag = 1;
                $count++;
            }
        }
        return $tabheads;
    }

    public function upload(Request $request)
    {
        $dataarray = array();
        $uploadedFile = $request->file('fileToUpload');
        $filename = $uploadedFile->getClientOriginalName();

        $lastmonth_month = Carbon::now()->subMonth()->month;
        $lastmonth_year = Carbon::now()->subMonth()->year;
        $lastmonth = $lastmonth_month."_".$lastmonth_year;

        Storage::disk('local')->putFileAs('files', $uploadedFile, $filename);
        $contents = Storage::get('/files/'.$filename);
        $lines = explode("\n", $contents);
        $heads = explode(",", $lines[0]);
        for($y=1;$y<count($lines);$y++){
            $tempdat = explode(",", $lines[$y]);
            
            for($i=0;$i<count($heads);$i++){
                $data[$y-1][$heads[$i]] = $tempdat[$i];
            }
        }
        
        $updates = DB::table('salary_'.$lastmonth)->get()->all();
        foreach($data as $update)
        {
            $query = 'JSON_CONTAINS(`salarydata`, \'{\"name\":\"'.$update['Employee ID'].'\"}\')';
            $name = DB::table('salary_'.$lastmonth)->whereRaw($query)->get()->first();
            if(isset($name))
            {
                $salarydata = json_decode($name->salarydata,true);
                
                $salarydata['hire_purchase'] = (float)$update['Hire Purchase'];
                $salarydata['fooding'] = (float)$update['Fooding'];
                $user_id = User::where('name', $salarydata['name'])->get()->first()->id;

                $total_loan = $this->loantotal($user_id);

                $salarydata['loan'] = (float)$total_loan;
                $salarydata['bonus'] = (float)$update['Bonus'];
                $salarydata['extra'] = (float)$update['Extra'];
                $salarydata['less'] = (float)$update['Less'];
                $salarydata['deduction_total'] =(float)$salarydata['pf_self'] +
                                                (float)$salarydata['pf_company'] +
                                                (float)$update['Hire Purchase'] + 
                                                (float)$salarydata['tds'] +
                                                (float)$update['Loan'] + 
                                                (float)$update['Less'] + 
                                                (float)$update['Fooding'];
                $salarydata['net_salary'] = (float)$salarydata['gross_salary']-
                                                (float)$salarydata['deduction_total'];

                $dataarray_tds = $salarydata;

                
                $salary_info = json_decode((Salary::where('user_id',$user_id)->get()->first()->salaryinfo),true);
                $dataarray['gender'] = $salary_info['gender'];
                $dataarray['dob'] = $salary_info['date_of_birth'];
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $dataarray['user'] = User::find($user_id);
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $dataarray['basicSalary'] = $dataarray_tds['basic'];
                $dataarray['perc_conveyance'] = (float)(100 * ($dataarray_tds['conveyance']/$dataarray_tds['basic']));
                $dataarray['perc_medical'] = (float)(100 * ($dataarray_tds['medical_allowance']/$dataarray_tds['basic']));
                $dataarray['perc_houserent'] = (float)(100 * ($dataarray_tds['house_rent']/$dataarray_tds['basic']));
                $dataarray['perc_pfcomp'] = (float)(100 * ($dataarray_tds['pf_company']/$dataarray_tds['basic']));

                
                $sp = $this::yearly_generation($dataarray,$dataarray_tds['bonus'],$dataarray_tds['extra'],$dataarray_tds['less']);
                // dd($user_id, $total_loan);
                
                $dataarray_tds['gross_salary'] = $sp['response_without_addons']['grossSalary'];
                $dataarray_tds['gross_total'] = $sp['response_without_addons']['grossTotal'];
                $dataarray_tds['tds'] = $sp['response_without_addons']['finalTax']/12 + $sp['addons_tax'];
                $dataarray_tds['loan'] = $total_loan;

                DB::table('salary_'.$lastmonth)->whereRaw($query)->update(['salarydata'=> json_encode($dataarray_tds)]);
                
            }
            //////////////////////////regenerating the table////////////////////////////////////////////    
            $tabheads = $this->tabhead_generation();//generates the tabheads from salarystructure
            $salary = $this->salary_extraction_from_lastmonth_table($lastmonth);//extracts the salary from lastmonth and puts in array
            ////////////////////////////////////////////////////////////////////////////////////      
            return view('salaries.index',['salaries'=>$salary, 'heads'=>$tabheads]); 
            // return back();   
        }      

    }

    private function TDS($TaxableSalary,$gender,$age)
    {
        $a = 0;
        
        if( $gender=='m' && $age<65)
        {
            $a = 250000;
        }
        elseif($gender == 'f' || $age>=65)
        {
            $a = 300000;
        }
        $taxableIncome = $TaxableSalary;
        $slab = array($a, 400000, 500000, 600000, 3000000);
        $slabperc = array(0, 10,15,20,25,30);
        $tax = array();
        $slabamount = array();

        // dd($taxableIncome);

        for($i=0;$i<count($slabperc);$i++){
            if($i == (count($slabperc)-1)){
                if($taxableIncome > 0){
                    $tax[$i] = $tax[$i]=$slabperc[$i]*$taxableIncome/100;
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

        return $response;
    }

    private function yearly_income_table_generator($year)//fix this to start from july instead of january
    {
        $x = 0;
        $y2 = (string)((int)$year + 1);
        Schema::connection('sqlite')->create('yearly_income_'.$year.'_'.$y2, function($table)
        {
            $table->increments('id');
            $table->integer('user_id')->nullable()->default(0);
            $table->string('user_name')->nullable()->default('');
            $table->json('yearly_income')->nullable();
            $table->timestamps();
        });
    }
    private function yearly_income_table_data_entry($id,$name,$yearlyProbableSalary,$year)
    {
        $x = 0;
        $y2 = (string)((int)$year + 1);
        DB::table('yearly_income_'.$year.'_'.$y2)->insert([
            'user_id' => $id,
            'user_name' => $name,
            // 'yearly_income' => json_encode(array_values($yearlyProbableSalary))
            'yearly_income' => json_encode($yearlyProbableSalary)
        ]);
    }
    private function yearly_income_table_data_update($id,$name,$yearlyProbableSalary,$year,$index)
    {
        $x = 0;
        $y2 = (string)((int)$year + 1);

        DB::table('yearly_income_'.$year.'_'.$y2)
        ->where('user_id',$id)
        ->update([
            'user_id' => $id,
            'user_name' => $name,
            // 'yearly_income' => json_encode(array_values($yearly_data))
            'yearly_income' => json_encode($yearlyProbableSalary)
        ]);
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
            $cysd['basicSalary'] += $ysd['salary'][$i]['basic'];
            $cysd['houseRent'] += $ysd['salary'][$i]['house_rent'];
            $cysd['conveyance'] += $ysd['salary'][$i]['conveyance'];
            $cysd['medicalAllowance'] += $ysd['salary'][$i]['medical_allowance'];
            $cysd['pfCompany'] += $ysd['salary'][$i]['pf_company'];
        }

        $basicSalary = $cysd['basicSalary'];
        $houseRent = $cysd['houseRent'];
        $conveyance = $cysd['conveyance'];
        $medicalAllowance = $cysd['medicalAllowance'];
        $bonus = $cysd['bonus'];
        $extra = $cysd['extra'];
        $less = $cysd['less'];

        $response['basicSalary'] = $basicSalary;
        $response['houseRent'] = $houseRent;
        $response['conveyance'] = $conveyance;
        $response['medicalAllowance'] = $medicalAllowance;
        $response['bonus'] = $bonus;
        $response['extra'] = $extra;
        $response['less'] = $less;

        $grossSalary = $cysd['basicSalary'] + $cysd['houseRent'] + $cysd['conveyance'] + $cysd['medicalAllowance'] + $bonus + $extra;
        $response['grossSalary'] = $grossSalary;

        $grossTotal = $grossSalary +$cysd['pfCompany'];
        $response['grossTotal'] = $grossTotal;

        $houseRentExempted = 300000;
        $response['houseRentExempted'] = $houseRentExempted;

        $HouseRentTR = $cysd['houseRent'];
        if(($HouseRentTR-$houseRentExempted)>=0)$HouseRentTR=$HouseRentTR-$houseRentExempted; else $HouseRentTR=0;
        $response['HouseRentTaxRemaining'] = $HouseRentTR;
        
        $conveyanceExempted = 30000;
        $response['conveyanceExempted'] = $conveyanceExempted;

        $conveyanceTR = $cysd['conveyance'];
        if(($conveyanceTR-$conveyanceExempted)>=0)$conveyanceTR=$conveyanceTR-$conveyanceExempted; else $conveyanceTR=0;
        $response['conveyanceTaxRemaining'] = $conveyanceTR;

        $medicalExempted = 120000;
        $response['medicalExempted'] = $medicalExempted;

        $medicalTR = $cysd['medicalAllowance'];
        if(($medicalTR-$medicalExempted)>=0)$medicalTR=$medicalTR-$medicalExempted; else $medicalTR=0;
        $response['medicalTaxRemaining'] = $medicalTR;

        $taxableFields = $HouseRentTR + $conveyanceTR + $medicalTR + $cysd['pfCompany'] + $bonus + $extra - $less;//not including extra yet
        $response['taxableFields'] = $taxableFields;

        $TaxableSalary = $cysd['basicSalary'] + $taxableFields;
        $response['TaxableSalary'] = $TaxableSalary;

        $info = $this->TDS($TaxableSalary,$gender,$age);

        $tax = $info['slabwisetax'];
        
        $response['slabwisetax'] = $info['slabwisetax'];
        $response['slab'] = $info['slab'];
        $response['slabamount'] = $info['slabamount'];

        $Tax = array_sum($tax);
        $response['Tax'] = $Tax;

        if($Tax > 5000)
        {
            $TI1 = $TaxableSalary - $cysd['pfCompany'];//is bonus includedin tax investment?
            $MaxInvestment = ceil($TI1 * (25/100));
            $response['MaxInvestment'] = $MaxInvestment;
            $TIRebate = ceil($MaxInvestment * (15/100));
            $response['TIRebate'] = $TIRebate;
            $finalTax = $Tax - $TIRebate;
            $response['finalTax'] = $finalTax;
        }
        else if($Tax>0 &&$Tax<=5000) 
        {
            $finalTax = 5000;
            $response['finalTax'] = $finalTax;
        }
        else
        {
            $response['MaxInvestment'] = 0;
            $response['TIRebate'] = 0;
            $response['finalTax'] = 0;
        }
        return $response;
    }
}
