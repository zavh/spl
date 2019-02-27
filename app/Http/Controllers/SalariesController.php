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
        $data = $this::calculation(0);
        dd($data);
        return view('salaries.index',['salaries'=>$data['salaries'],'heads'=>$data['heads']]);
        
    }

    private function mapping($date)
    {
        $month = strtotime($date);
        $month = date('m', $month);
        $n = $month;
        //////////date calc/////////////////////////////////////////////////////////
        switch ($n) {
            case "7":
                $mapped_month = 12;
                break;
            case "8":
                $mapped_month = 11;
                break;
            case "9":
                $mapped_month = 10;
                break;
            case "10":
                $mapped_month = 9;
                break;
            case "11":
                $mapped_month = 8;
                break;
            case "12":
                $mapped_month = 7;
                break;
            case "1":
                $mapped_month = 6;
                break;
            case "2":
                $mapped_month = 5;
                break;
            case "3":
                $mapped_month = 4;
                break;
            case "4":
                $mapped_month = 3;
                break;
            case "5":
                $mapped_month = 2;
                break;
            case "6":
                $mapped_month = 1;
                break;         
            default:
                $mapped_month = "error";
                break;
        } 
        return $mapped_month;
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
    private function update_salary()
    {
        #insert code here ....
    }

    private function initial_salary_generator()
    {
        $users = User::where('active','1')->actual()->get()->all();
        $count = 0;

        $salary = array();
        $tabheads = array('Employee ID','Basic', 'Date of Joining');
        $flag = 0;
        $fraction = array();

        $lastmonth = Carbon::now()->subMonth()->startOfMonth();

        foreach($users as $index=>$user)
        {
            $sstructure = $user->salarystructure;

            $x = $user->salary;
            $salary[$count]['name'] = $x->user->name;
            $si = json_decode($x->salaryinfo);

            $salary[$count]['basic'] = $si->basic;
            $salary[$count]['join_date'] = $si->join_date;

            

            if(strtotime(date("Y-m-01"))<strtotime($si->join_date))
                continue;

            if(empty($sstructure)){
                $salary[$count] = null;
            }
            else 
            {
                $ss = json_decode($sstructure->structure);
                foreach($ss as $breakdown){
                    if($flag == 0)
                        $tabheads[count($tabheads)]=$breakdown->param_uf_name;
                    $salary[$count][$breakdown->param_name] = ($salary[$count]['basic'] * $breakdown->value)/100;
                    $profile[$breakdown->param_name] = $breakdown->value;
                }

                $sp = $this->salaryProfile($si,$profile, 0,$user, 0,0,0);
                $gross_salary = $sp['response_without_addons']['grossSalary']/12;
                $gross_total = $sp['response_without_addons']['grossTotal']/12;
                $tds = $sp['response_without_addons']['finalTax']/12;

                $total_loan = $this->loantotal($user->id);

                $salary[$count]['loan'] = (float)$total_loan;

                $salary[$count]['bonus'] = 0;
               
                $salary[$count]['gross_salary'] = $gross_salary;
                $tabheads['gross_salary'] = "Gross Salary";
                
                $salary[$count]['gross_total'] = $gross_total;
                $tabheads['gross_total'] = "Gross Total";

                $salary[$count]['tds'] = $tds;

                $salary[$count]['deduction_total'] = $salary[$count]['hire_purchase']+
                                                    $salary[$count]['loan']+
                                                    $salary[$count]['less']+
                                                    $salary[$count]['tds'];
                $tabheads['deduction_total'] = "Deduction Total";

                $salary[$count]['net_salary'] = $salary[$count]['gross_salary']-
                                                    $salary[$count]['deduction_total'];
                $tabheads['net_salary'] = "Net Salary";
                $flag = 1;
                $count++;
            }
        }
        $data = ['salary'=>$salary, 'heads'=>$tabheads, "fraction"=>$fraction];
        return $data;
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

    private function salary_extraction_from_lastmonth_table($lastmonth)
    {
        $salarydata = DB::table('salary_'.$lastmonth)->get()->all();
        $salary = array();
        $i = 0;
        foreach($salarydata as $sal)
        {
            $salary[$i] = json_decode($sal->salarydata,true);
            $i++;
        }
        return $salary;
    }

    // public function taxtable($id)
    // {
    //     $user = User::find($id);

    //     $si = json_decode($user->salary->salaryinfo);
    //     $sstructure = $user->salarystructure;
    //     $ss = json_decode($sstructure->structure);
        
    //     foreach($ss as $breakdown){
    //         $profile[$breakdown->param_name] = $breakdown->value;
    //     }
    //     $r = $this::salaryProfile($si,$profile, 1,$user);//response

    //     return view('salaries.taxtable',['response'=>$r['tds'],'dataarray'=>$r['da']]);
    // }

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
                // $dataarray['bonus'] = $dataarray_tds['bonus'];
                // $dataarray['extra'] = $dataarray_tds['extra'];
                // $dataarray['less'] = $dataarray_tds['less'];
                
                $sp = $this::yearly_generation($dataarray,$dataarray_tds['bonus'],$dataarray_tds['extra'],$dataarray_tds['less']);
                // dd($user_id, $total_loan);
                
                $dataarray_tds['gross_salary'] = $sp['response_without_addons']['grossSalary'];
                $dataarray_tds['gross_total'] = $sp['response_without_addons']['grossTotal'];
                $dataarray_tds['tds'] = $sp['response_without_addons']['finalTax']/12 + $sp['addons_tax'];
                $dataarray_tds['loan'] = $total_loan;
                // dd($dataarray_tds);
                // $Salarydata['tds'] = (float)($response['finalTax']/12);

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

    private function calculation($bonus_flag)
    {   
        $count = 0;
        $salary = array();
        $flag = 0;
        $sp_update_date=array();
        $sp=0;
        $lastmonth_month = Carbon::now()->subMonth()->month;
        $lastmonth_year = Carbon::now()->subMonth()->year;
        $lastmonth = $lastmonth_month."_".$lastmonth_year;

        $salarystructure_update_flag = 0;

        ################# Salary Profile Check Strts #################
        //generates the monthly table that checks if salarystructures have been updated and updates it
        if(!Schema::hasTable('salaryupdate_'.$lastmonth))
        {
            echo "no temp table                 ";
            $this->salaryupdate_generator($lastmonth); 
        }
        else
        {
            echo "temp table found                 ";
            $salaryprofile_current =  DB::table('salaryupdate_'.$lastmonth)->get()->all();
            $c1 = count($salaryprofile_current); //total number of profiles in temp table
            $c2 = SalaryStructure::count();//total number of profiles in profiles table
            
            if($c1==$c2)//no new profiles added
            {
                echo "no new profiles                 ";
                foreach($salaryprofile_current as $sp)
                {
                    $id1 = $sp->structureid;
                    $d1 = $sp->update;
                    $ssd1 = SalaryStructure::find($id1)->updated_at;
                    $ssd1 = $ssd1->toDateTimeString();
                    if($d1 != $ssd1) //updated later
                    {
                        echo "date updated                 ";
                        $salarystructure_update_flag = 1;
                        $this->salary_updater($lastmonth); //function updates the table that checks the last update of the salarystructure table
                    }
                    else
                    {
                        echo "date not updated                 ";
                    }
                }
            }
            else //profile updated
            {
                echo "new profile added                 ";
                $salarystructure_update_flag = 1;
                $this->salary_updater($lastmonth);//function updates the table that checks the last update of the salarystructure table
            }
        }
        ################# Salary Profile Check Ends #################
        
        if (!Schema::hasTable('salary_'.$lastmonth) || $salarystructure_update_flag == 1)//this part will recalculate the table
        {      
            if (Schema::hasTable('salary_'.$lastmonth)){
                Schema::drop('salary_'.$lastmonth);//drop the table first, it needs to be recalculated
            }
            echo "update required, needs recalculation                 ";
            
            $this->salary_generator($lastmonth);//generates the table for the salary_lastmonth
            $data = $this->initial_salary_generator();//freshly calculates the salaries of the LAST month
            $salary =$data['salary'];
            $tabheads = $data['heads'];
            $fraction = $data['fraction'];
            if($fraction != 0)
            {
                $this->store_salary($salary,$lastmonth);//inserts the salary in the freshly generated table
            }
        }
        else//this will generate the table from the salary_month table
        { 
            echo "update not required, just displaying                     "; 
            $tabheads = $this->tabhead_generation();//generates the tabheads from salarystructure
            $salary = $this->salary_extraction_from_lastmonth_table($lastmonth);//extracts the salary from lastmonth and puts in array  
                         
        }
    $data = ['salaries'=>$salary,'heads'=>$tabheads];
    return $data;
    }

    private function salaryProfile($user, $ss, $flag, $userdata,$bonus,$extra,$less)
    {
        $ss = (object)$ss;
        $dataarray = array(
            "gender"=>$user->gender,
            "dob"=>$user->date_of_birth,
            "user"=>$userdata,
            "basicSalary"=>$user->basic,
            "perc_conveyance"=>$ss->conveyance,
            "perc_medical"=>$ss->medical_allowance, 
            "perc_houserent"=>$ss->house_rent,
            "perc_pfcomp"=>$ss->pf_company
        );
        if($flag == 0){ //called from index
            $response = $this::yearly_generation($dataarray,$bonus,$extra,$less);//caled from index, no input for bonus, extra, less so, 0 values
            // dd($response);
            return $response;
        }
        // else if($flag == 1){ //called from taxtable
        //     $response['tds'] = $this::yearly_generation($dataarray,$dataarray['bonus'],$dataarray['extra'],$dataarray['less']);//taxtable will change, 0 values for now
        //     $response['da'] = $dataarray;
        //     return $response;
        // }
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
        Schema::connection('mysql')->create('yearly_income_'.$year.'_'.$y2, function($table)
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

    private function yearly_income_data_extractor($year,$id)
    {
        $x = 0;
        $y2 = (string)((int)$year + 1);
        $yearlydata = DB::table('yearly_income_'.$year.'_'.$y2)->where('user_id',$id)->get();
        return json_decode($yearlydata[0]->yearly_income,true);
    }

    private function cumulative_yearly_generator($yearly_income)
    {
        $cysd = array(
            "basicSalary"=>0,
            "houseRent"=>0,
            "medicalAllowance"=>0,
            "conveyance"=>0,
            "pfCompany"=>0,
            // "extra"=>0,
            // "bonus"=>0      
            );//cumulative yearly salary data
        // dd($yearly_income);
        for ($i=0; $i < 12; $i++) 
        { 
            $cysd["basicSalary"] += (float)$yearly_income[$i]['basicSalary'] ;
            $cysd["houseRent"] += (float)$yearly_income[$i]['houseRent'];
            $cysd["medicalAllowance"] += (float)$yearly_income[$i]['medicalAllowance'];
            $cysd["conveyance"] += (float)$yearly_income[$i]['conveyance'];
            $cysd["pfCompany"] += (float)$yearly_income[$i]['pfCompany'];
            // $cysd["extra"] = (float)$yearly_income[$i]['extra'];
            // $cysd["bonus"] = (float)$yearly_income[$i]['bonus'] * 2;
            // $cysd["bonus"] = 0;
 
        }
        return $cysd;
        // dd($cysd);
    }

    private function TDS_generation($yearly_income,$response,$number_of_months_remaining,$number_of_months_passed,$joindate,$enddate)
    {
        $remaining = $number_of_months_remaining;
        $passed = $number_of_months_passed;
        $finalTax = $response['response_without_addons']['finalTax'];
        $bonus_tax = $response['bonus_tax'];
        $extra_tax = $response['extra_tax'];
        $less_tax = $response['less_tax'];

        $accumulated_tax = 0;
        for ($i=0; $i < $passed; $i++) { 
            $accumulated_tax += $yearly_income[$i]["TDS"]; 
        }

        if($remaining != 0)
        {
            $TDS = (float)(($finalTax - $accumulated_tax)/$remaining);
        }
        else
        {
            $TDS = 0;
        }
        // echo "<pre>".
        //             "Final tax -> ".$finalTax.
        //             " Accumulatedtax ->".$accumulated_tax.
        //             " TDS ->".$TDS.
        //             " join date ->".$joindate.
        //             " end date ->".$enddate.
        //             " bonus tax ->".$bonus_tax.
        //             " extra tax ->".$extra_tax.
        //             " less tax ->".$less_tax.       
        //     "</pre>";
        for ($i = $passed; $i < 12; $i++) 
        {
            $yearly_income[$i]["TDS"] = (float)$TDS + $bonus_tax +$extra_tax + $less_tax;
            $bonus_tax = $extra_tax = $less_tax = 0;
            $yearly_income[$i]["bonus"] = $yearly_income[$i]["extra"] = $yearly_income[$i]["less"] = 0;
        }
       
        return $yearly_income;
        // dd($finalTax,$accumulated_tax,$remaining);
    }

    private function fraction_calculation($sal)
    {
        $joindate = Carbon::parse($sal->join_date);

        $join_fraction = (float)0.00;
        $end_fraction = (float)0.00;

        $lastmonth_start = Carbon::now()->subMonth()->startOfMonth();
        $lastmonth_end = Carbon::now()->subMonth()->endOfMonth();

        $joindate_d = $joindate->day;
            
        if($joindate >= $lastmonth_start && $joindate <= $lastmonth_end)//joined sometime duing last month, can be first or last day
        {
            $join_fraction = (float)((($lastmonth_start->daysInMonth-$joindate_d)+1)/$lastmonth_start->daysInMonth);
            // echo "Join ->".$join_fraction;
        }
        if($joindate > $lastmonth_end)//hasnt joined yet
        {
            $join_fraction = (float)0;
            // echo "Join ->".$join_fraction;
        }
        if($joindate < $lastmonth_start)//joined before last month starts
        {
            $join_fraction = (float)1;
            // echo "Join ->".$join_fraction;
        }
        
        if ($sal->end_date == null)//not terminated, no need to consider end date
        {
            $end_fraction = (float)0;
            // echo "end ->".$end_fraction;//not terminated
        }
        else
        {
            $enddate = Carbon::parse($sal->end_date);       
            $enddate_d = $enddate->day;

            if($enddate >= $lastmonth_start && $enddate <= $lastmonth_end)//both join and end date in last month
            {
                $end_fraction = (float)((($lastmonth_start->daysInMonth-$enddate_d))/$lastmonth_start->daysInMonth);
                // echo "end ->".$end_fraction;
            }
            if($enddate < $lastmonth_start)//hasnt joined yet
            {
                $end_fraction = (float)0;
                // echo "end ->".$end_fraction;
            }
            if($enddate > $lastmonth_end)//joined before last month starts
            {
                $end_fraction = (float)0;
                // echo "end ->".$end_fraction;
            }
            if($enddate < $lastmonth_start && $joindate < $lastmonth_start)
            {
                $join_fraction = (float)0;
                // echo "Join ->".$join_fraction;
                $end_fraction = (float)0;
                // echo "end ->".$end_fraction;
            }
        }

        $fraction = $join_fraction-$end_fraction;
        
        return $fraction;
    }

    private function yearly_generation($dataarray,$bonus_manual,$extra,$less)
    { 
        // echo "I was called";
        $year = Carbon::now()->year;

        $gender = $dataarray['gender'];
        $response['gender'] = $gender;

        $age = Carbon::parse($dataarray['dob'])->age;
        $response['age'] = $age;

        $basicSalary = $dataarray['basicSalary'];//monthly
        $response['basicSalary'] = $basicSalary;

        $conveyance = (float)($basicSalary * ($dataarray['perc_conveyance']/100));//monthly
        $response['conveyance'] = $conveyance;

        $medicalAllowance = (float)($basicSalary * ($dataarray['perc_medical']/100));
        $response['medicalAllowance'] = $medicalAllowance;

        $houseRent = (float)($basicSalary * ($dataarray['perc_houserent']/100));
        $response['houseRent'] = (float)($houseRent);

        if($bonus_manual > 0)
        {
            $bonus = $bonus_manual;
        }
        else
        {
            $bonus = 0;            
        }
        $response['bonus'] = (float)$bonus;

        $response['extra'] = (float)$extra;

        $response['less'] = (float)$less;


        $pfCompany = (float)($basicSalary * ($dataarray['perc_pfcomp']/100));
        $response['pfCompany'] = $pfCompany;

        $user = $dataarray['user'];
        $response['user'] = $user;

        

        $x = $user->salary;
        $sal = json_decode($x->salaryinfo);
        // dd($x);
        // var_dump($sal);

        $jd = Carbon::parse($sal->join_date);
        if($sal->end_date != null)
        {
            $td = Carbon::parse($sal->end_date);
        }
        else
        {
            $td = "null";
        }
        
        
        $fraction = $this->fraction_calculation($sal);
        
        //Started and ended last month
        // if($enddate_y == $lastmonth_e_y && $enddate_m == $lastmonth_e_m && ($enddate_d - $joindate_d)<$lastmonth_start->daysInMonth)
        // {
        //     $join_fraction = (float)((($lastmonth_start->daysInMonth-$joindate_d)+1)/$lastmonth_start->daysInMonth);
        //     $end_fraction = (float)((($lastmonth_start->daysInMonth-$enddate_d))/$lastmonth_start->daysInMonth);
        // }

        $user_id = $user->id;
        $user_name = $user->name;
        $year = Carbon::now()->submonth()->year;
        
        $current_date = Carbon::now()->submonth()->format('Y-m-d');
        $number_of_months_remaining = $this->mapping($current_date);
        $response['number_of_months_remaining'] = $number_of_months_remaining;

        $number_of_months_passed = 12-$number_of_months_remaining;
        $response['number_of_months_passed'] = $number_of_months_passed;
        $x = 0;


        if($number_of_months_remaining <7){
            $year = $year - 1;
        }
        $y2 = (string)((int)$year + 1);

        // $this->yearly_income_table_generator($year);//generates yearly table if not exists
        if (!Schema::hasTable('yearly_income_'.$year.'_'.$y2))//no table, generate
        {
            $this->yearly_income_table_generator($year);//generates yearly table if not exists
        }
        // else if($number_of_months_remaining == 12)//has table, regenerate
        // {
        //     Schema::drop('yearly_income_'.$year.'_'.$y2);
        //     $this->yearly_income_table_generator($year);//generates yearly table if not exists
        // }

        $index = 12 - $number_of_months_remaining;

        if($index > 0)
        {
            $dbdata = DB::table('yearly_income_'.$year.'_'.$y2)->where('user_id',$user->id)->get()->all();
            // dd(json_decode($dbdata->yearly_income));  
            if(count($dbdata)>0)//user exists
            {
                $yearly_income = json_decode($dbdata[0]->yearly_income,true);
                             
            }
            else
            {
                for($i=0;$i<12;$i++)
                {
                    $yearly_income[$i]["basicSalary"] = 0;
                    $yearly_income[$i]["houseRent"] = 0;
                    $yearly_income[$i]["medicalAllowance"] = 0;
                    $yearly_income[$i]["conveyance"] = 0;
                    $yearly_income[$i]["pfCompany"] = 0;
                    $yearly_income[$i]["bonus"] = 0;
                    $yearly_income[$i]["extra"] = 0;
                    $yearly_income[$i]["less"] = 0;
                    $yearly_income[$i]["month"]  = '';
                    $yearly_income[$i]["fraction"] = 0;  
                    $yearly_income[$i]["TDS"] = 0;
                }
            }
        }
        $end_date = Carbon::parse($sal->end_date);
        $lastmonth_start = Carbon::now()->subMonth()->startOfMonth();
        $lastmonth_end = Carbon::now()->subMonth()->endOfMonth();
        for ($i = $index; $i < 12; $i++) 
        {
            $yearly_income[$i]["basicSalary"] = (float)$basicSalary * $fraction;
            $yearly_income[$i]["houseRent"] = (float)$houseRent * $fraction;
            $yearly_income[$i]["medicalAllowance"] = (float)$medicalAllowance * $fraction;
            $yearly_income[$i]["conveyance"] = (float)$conveyance * $fraction;
            $yearly_income[$i]["pfCompany"] = (float)$pfCompany * $fraction;
            $yearly_income[$i]["bonus"] = (float)$bonus;
            $yearly_income[$i]["extra"] = (float)$extra;
            $yearly_income[$i]['less'] = (float)$less;
            $yearly_income[$i]["month"]  = Carbon::now()->subMonth()->month;
            $yearly_income[$i]["fraction"] = $fraction;
            // echo "<pre>fraction for ".$user->name." = ".$fraction."</pre>";
            if($sal->end_date != null  && (($end_date < $lastmonth_start) || ($end_date >= $lastmonth_start && $end_date <= $lastmonth_end)))
            {
                $fraction = (float)0;//ternminated within or before last month
            }
            else if ($sal->end_date == null  || ($end_date > $lastmonth_end) )
            {
                $fraction = (float)1;
            }
            // $bonus = $extra = $less = 0;
        }

        if($end_date >= $lastmonth_start && $end_date <= $lastmonth_end)
        {
            // echo "if trigerred";
            $user = User::find($user_id);
            $user->active = 0;
            $user->save();
            $salarydata = Salary::where('user_id',$user->id)->get()->first();
            $sl = json_decode($salarydata->salaryinfo);
            $sl->tstatus = "t";
            $sl = json_encode($salarydata->salaryinfo);
            $salarydata->save();
        }
        else
        {
            // echo "else trigerred";
            $cysd = $this->cumulative_yearly_generator($yearly_income);//cumulative yearly salary data
            // dd($yearly_income, $cysd);

            $response_without_addons['taxData'] = $this->income_tax_calculation($cysd,$gender,$age,0,0,0);//returning final tax and relevent info
            $response['response_without_addons'] = $response_without_addons['taxData'];

            $tax_without_bonus = $response_without_addons['taxData']['finalTax'];
            $response_with_bonus['taxData'] = $this->income_tax_calculation($cysd,$gender,$age,$bonus,0,0);//returning final tax and relevent info
            $tax_with_bonus = $response_with_bonus['taxData']['finalTax'];
            $bonus_tax = $tax_with_bonus - $tax_without_bonus;
            $response['response_with_bonus'] = $response_with_bonus['taxData'];
            $response['bonus_tax'] = $bonus_tax;

            $tax_without_extra = $response_without_addons['taxData']['finalTax'];
            $response_with_extra['taxData'] = $this->income_tax_calculation($cysd,$gender,$age,0,$extra,0);//returning final tax and relevent info
            $tax_with_extra = $response_with_extra['taxData']['finalTax'];
            $extra_tax = $tax_with_extra - $tax_without_extra;
            $response['response_with_extra'] = $response_with_extra['taxData'];
            $response['extra_tax'] = $extra_tax;

            $tax_without_less = $response_without_addons['taxData']['finalTax'];
            $response_with_less['taxData'] = $this->income_tax_calculation($cysd,$gender,$age,0,0,$less);//returning final tax and relevent info
            $tax_with_less = $response_with_less['taxData']['finalTax'];
            $less_tax = $tax_without_less - $tax_with_less;//less is subtracted
            $response['response_with_less'] = $response_with_less['taxData'];
            $response['less_tax'] = $less_tax;

            $tax_without_addons = $response_without_addons['taxData']['finalTax'];
            $response_with_addons['taxData'] = $this->income_tax_calculation($cysd,$gender,$age,$bonus,$extra,$less);//returning final tax and relevent info
            $tax_with_addons = $response_with_addons['taxData']['finalTax'];
            $addons_tax = $tax_with_addons - $tax_without_addons;//less is subtracted
            $response['response_with_addons'] = $response_with_addons['taxData'];
            $response['addons_tax'] = $addons_tax;
            
            $yearly_income = $this->TDS_generation($yearly_income,$response,$number_of_months_remaining,$number_of_months_passed,$jd,$td);
        }

        $count = DB::table('yearly_income_'.$year.'_'.$y2)->where('user_id',$user->id)->get();

        if(count($count)>0)
        {
            $this->yearly_income_table_data_update($user_id,$user_name,$yearly_income,$year,$index);
        }
        else
        {            
            $this->yearly_income_table_data_entry($user_id,$user_name,$yearly_income,$year);
        }
        return $response;
    }

    private function income_tax_calculation($cysd,$gender,$age,$bonus,$extra,$less)
    {
        $basicSalary = $cysd['basicSalary'];
        $response['basicSalary'] = $basicSalary;

        $houseRent = $cysd['houseRent'];
        $response['houseRent'] = $houseRent;

        $conveyance = $cysd['conveyance'];
        $response['conveyance'] = $conveyance;

        $medicalAllowance = $cysd['medicalAllowance'];
        $response['medicalAllowance'] = $medicalAllowance;

        $response['bonus'] = $bonus;
        $response['extra'] = $extra;
        $response['less'] = $less;

        // $extra = $cysd['extra'];
        // $response['extra'] = $extra;

        $grossSalary = $cysd['basicSalary'] + $cysd['houseRent'] + $cysd['conveyance'] + $cysd['medicalAllowance'] + $bonus + $extra;
        $response['grossSalary'] = $grossSalary;

        $grossTotal = $grossSalary +$cysd['pfCompany'];
        $response['grossTotal'] = $grossTotal;

        $houseRentExempted = 300000;
        $response['houseRentExempted'] = $houseRentExempted;

        // $HouseRentTR = $houseRent*12;
        $HouseRentTR = $cysd['houseRent'];
        if(($HouseRentTR-$houseRentExempted)>=0)$HouseRentTR=$HouseRentTR-$houseRentExempted; else $HouseRentTR=0;
        $response['HouseRentTaxRemaining'] = $HouseRentTR;
        
        $conveyanceExempted = 30000;
        $response['conveyanceExempted'] = $conveyanceExempted;

        // $conveyanceTR = $conveyance*12;
        $conveyanceTR = $cysd['conveyance'];
        if(($conveyanceTR-$conveyanceExempted)>=0)$conveyanceTR=$conveyanceTR-$conveyanceExempted; else $conveyanceTR=0;
        $response['conveyanceTaxRemaining'] = $conveyanceTR;

        $medicalExempted = 120000;
        $response['medicalExempted'] = $medicalExempted;

        // $medicalTR = $medicalAllowance*12;
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

        // dd($response);
        return $response;

    }

    private function loantotal($user_id)
    {
        $salary = Salary::where('user_id',$user_id)->get()->first();
        $salary_id = $salary->id;
        $loans = Loan::where('salary_id',$salary_id)->get()->all();
        // dd($loans);
        $total_loan = 0;
        foreach($loans as $loan)
        {
            $lmstart = Carbon::now()->subMonth()->startOfMonth();
            $lmend = Carbon::now()->subMonth()->endOfMonth();
            $startdate = Carbon::parse($loan->start_date);
            $enddate = Carbon::parse($loan->end_date);
            if( ($startdate>$lmstart && $startdate<$lmend) || ($startdate<$lmstart) )
            {
                if( ($enddate>$lmstart && $enddate<$lmend) || ($enddate>$lmend) )
                {
                    $monthly_loan = $loan->amount/$loan->installments;
                    $total_loan += $monthly_loan; 
                }
            }
        }
        return $total_loan;
    }
}
