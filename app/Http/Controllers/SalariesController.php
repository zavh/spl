<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Salary;
use App\Loan;
use Carbon\Carbon;
use App\Http\Traits\SalaryGenerator;
use App\Http\Presentations\TaxConfig;

use App\User;
use Illuminate\Support\Facades\Storage;

class SalariesController extends Controller
{
    use SalaryGenerator;
    private $taxable_income = 0;
    private $yearly_tds = 0;

    public function index()
    {
        return view('salaries.query');        
    }

    public function dbcheck($year = null){

        $today = date("Y-m-d");
        if($today > date("Y-07-31")){
            $currentFromYear = date("Y") + 0;
            $currentToYear = date("Y") + 1;
        }
        else{
            $currentFromYear = date("Y") - 1;
            $currentToYear = date("Y") + 0;
        }

        //Determining default or user input
        if($year == null){ //Default
            $fromYear = $currentFromYear;
            $toYear = $currentToYear;
            $year = Carbon::parse($fromYear.'-'.date('m').'-'.date('d'))->subMonth()->format("Y-n"); //subMonth choses the previous month
            $thisMonth = Carbon::parse($today)->subMonth()->format("n");    //subMonth choses the previous month
        }
        else {
            $targetPeriod = explode('-', $year);
            $thisMonth = $targetPeriod[1];
            $fromYear = $targetPeriod[0] + 0;
            $toYear = $targetPeriod[0] + 1;
        }
        
        // Determine the array index according to month. 
        // This array index matches with the yearly json data stored in the db
        // This index determines data of which array element should be shown
        // July this year (7th month of year) has the array index of 0
        // and June next year (6th month of year) has the array index of 12 of 
        if($thisMonth > 6 ){
            $month = $thisMonth - 7;
        }
        else {
            $month = 5 + $thisMonth;
        }
        $db_table_name = 'yearly_income_'.$fromYear."_".$toYear;

        if(Schema::hasTable($db_table_name)){
            // This is going to fetch tables from db and then process the presentation
            $db = DB::table($db_table_name)->select('profile', 'salary')->get();
            for($i=0;$i<count($db);$i++){
                $d[$i]['salary'] = json_decode($db[$i]->salary);
                $d[$i]['profile'] = json_decode($db[$i]->profile);
            }
        }
        else {
            if($fromYear != $currentFromYear){
                $response['status'] = 'fail';
                $response['year'] = $fromYear;
                $response['month'] = $month;
                $response['message'] = 'Fresh generation of salaries from previous or future year will be misleading. A lot of factors may have/will be changed, ex: basic, salary structure, tax information. Hence this is prohibited';
                return $response;
            }
            else {                
                
                // save this $db into database 
                $d = $this->initial_salary_generator("$fromYear-07-01", "$toYear-06-30");
                // call yearly_income_table_generator and yearly_income_table_data_entry
                $this->yearly_income_table_generator($db_table_name);
                for($i=0;$i<count($d);$i++){
                    $this->yearly_income_table_data_entry($d[$i],$db_table_name);
                }
                // then send this reponse to prepare presentation
            }
        }
        $response = $this->presentation($d, $year, $month);
        $response['status'] = 'success';
        $response['fromYear'] = $fromYear;
        $response['toYear'] = $toYear;
        $response['month'] = $thisMonth;

        return response()->json($response);
    }

    private function yearly_income_table_generator($table_name)//fix this to start from july instead of january
    {
        Schema::connection('mysql')->create($table_name, function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->json('salary');
            $table->json('profile');
            $table->json('structure');
            $table->json('tax_config');
            $table->timestamps();
        });
    }
    
    private function yearly_income_table_data_update($name, $table, $updates)
    {
        DB::table($table)
        ->where('name',$name)
        ->update($updates);
    }

    private function presentation($d, $year, $month){
        $d = json_decode(json_encode($d));
        $response['tabheads'] = $this->tabhead_generation();
        $t = explode('-',$year);
        if($t[1]<7) $t[0]++;
        $target_month = Carbon::create($t[0],$t[1]+1, 1, 0, 0, 0, 'Asia/Dhaka');
        $rc = 0;
        for($i=0;$i<count($d);$i++){
            if(Carbon::parse($d[$i]->profile->join_date)->gt($target_month)) continue;
            $user = User::find($d[$i]->profile->id);
            $salaryinfo = json_decode($user->salary->salaryinfo);
            $response['data'][$i]['employee_id'] = $d[$i]->profile->employee_id;
            $response['data'][$i]['name'] = $d[$i]->profile->name;
            $response['data'][$i]['join_date'] = $d[$i]->profile->join_date;
            $response['data'][$i]['basic'] = number_format($d[$i]->salary[$month]->basic, 2);
            $response['data'][$i]['house_rent'] = number_format($d[$i]->salary[$month]->house_rent, 2);
            $response['data'][$i]['conveyance'] = number_format($d[$i]->salary[$month]->conveyance, 2);
            $response['data'][$i]['medical_allowance'] = number_format($d[$i]->salary[$month]->medical_allowance, 2);
            $response['data'][$i]['pf_self'] = number_format($d[$i]->salary[$month]->pf_self, 2);
            $response['data'][$i]['pf_company'] = number_format($d[$i]->salary[$month]->pf_company, 2);
            $response['data'][$i]['bonus'] = number_format($d[$i]->salary[$month]->bonus, 2);
            if(!isset($response['indexing'][$user->department->id])){
                // $response['indexing'][$user->department->id] = array();
                $response['indexing'][$user->department->id]['bank'] = array();
                $response['indexing'][$user->department->id]['cash'] = array();
                if($salaryinfo->pay_out_mode == 'BANK')
                    $response['indexing'][$user->department->id]['bank'][0] = $rc++;
                else
                    $response['indexing'][$user->department->id]['cash'][0] = $rc++;   
            }
            else {
                if($salaryinfo->pay_out_mode == 'BANK'){
                    $x = count($response['indexing'][$user->department->id]['bank']);
                    $response['indexing'][$user->department->id]['bank'][$x] = $rc++;
                }
                else {
                    $x = count($response['indexing'][$user->department->id]['cash']);
                    $response['indexing'][$user->department->id]['cash'][$x] = $rc++;
                }

            }
            // if($salaryinfo->pay_out_mode == 'BANK')
            if(is_object($d[$i]->salary[$month]->loan)){
                $total_loan = $d[$i]->salary[$month]->loan->sum;
                $response['data'][$i]['loan'] = number_format($total_loan,2);
            }
            else {
                $response['data'][$i]['loan'] = 0;
                $total_loan = 0;
            }
                
            $response['data'][$i]['extra'] = $d[$i]->salary[$month]->extra;
            $response['data'][$i]['less'] = $d[$i]->salary[$month]->less;
            $response['data'][$i]['fooding'] = $d[$i]->salary[$month]->fooding;
            $response['data'][$i]['monthly_tax'] = number_format($d[$i]->salary[$month]->monthly_tax, 2);
            
            $deduction_total = 
                        $d[$i]->salary[$month]->pf_self + 
                        $total_loan +
                        $d[$i]->salary[$month]->less +
                        $d[$i]->salary[$month]->monthly_tax +
                        $d[$i]->salary[$month]->fooding
                        ;
            $response['data'][$i]['deduction_total'] = number_format($deduction_total, 2);
            $gross_salary = 
                        $d[$i]->profile->basic * $d[$i]->salary[$month]->fraction +
                        $d[$i]->salary[$month]->house_rent +
                        $d[$i]->salary[$month]->conveyance +
                        $d[$i]->salary[$month]->medical_allowance +
                        $d[$i]->salary[$month]->bonus +
                        $d[$i]->salary[$month]->extra
                        ;
            $response['data'][$i]['gross_salary'] = number_format($gross_salary, 2);
            $response['data'][$i]['gross_total'] = number_format($gross_salary + $d[$i]->salary[$month]->pf_company, 2);
            $response['data'][$i]['net_salary'] = number_format($gross_salary - $deduction_total,2);
            if($salaryinfo->pay_out_mode == 'BANK'){
                $response['bankaccounts'][$d[$i]->profile->employee_id] = [
                    'bank_name' => $d[$i]->profile->bank_name,
                    'bank_branch' => $d[$i]->profile->bank_branch,
                    'bank_account_name' => $d[$i]->profile->bank_account_name,
                    'bank_account_number' => $d[$i]->profile->bank_account_number,
                ];
            }
        }
        return $response;
    }

    private function initial_salary_generator($fromYear, $toYear)
    {
        $users = User::actual()->get();
        $data = array();
        foreach($users as $index=>$user){
            //Step 1: Generate Yearly Salary
            $data[$index] = $this->yearly_generator($user, $fromYear, $toYear);
            //Step 2: Generate tentative yearly tax
            $tax_config = $this->income_tax_calculation($data[$index]);
            $data[$index]['tax_config'] = $tax_config;
            //Step 3: Generate monthly tax
            $data[$index] = $this->generate_monthly_tax($data[$index], $tax_config['finalTax']);
        }
        return $data;
    }

    private function tabhead_generation()
    {
        $tabheads = array(
            'employee_id'=>'Employee ID',
            'name'=>'Name',
            'join_data'=> 'Date of Joining',
            'basic'=>'Basic',
            'house_rent'=> 'House Rent',
            'conveyance'=> 'Conveyance',
            'medical_allowance'=> 'Medical Allowance',
            'pf_self'=> 'PF Self',
            'pf_company'=> 'PF Company',
            'bonus'=> 'Bonus',
            'loan'=> 'Loan',
            'extra'=> 'Extra',
            'less'=> 'Less',
            'fooding'=>'Fooding',
            'monthly_tax'=>'Monthly Tax',
            'deduction_total'=> 'Deduction Total',
            'gross_salary'=> 'Gross Salary',
            'gross_total'=> 'Gross Total',
            'net_salary'=> 'Net Salary'
        );
        return $tabheads;
    }

    private function taxable_income_changers()
    {
        $tabheads = array();
        $tabheads['monthchanger'] = array(
            'bonus' => 'Bonus',
            'extra' => 'Extra',
            'less' => 'Less',
        );
        $tabheads['yearchanger'] = array(
            'basic' => 'Basic',
        );
        return $tabheads;
    }

    private function tax_change_preparation($s, $p){
        $profile = json_decode($p);
        $ysd['profile'] = (object)[
            'gender' => $profile->gender,
            'date_of_birth' => $profile->date_of_birth
        ];
        $ysd['salary'] = $s;
        return $this->income_tax_calculation($ysd);
    }
    private function basicChanger($d){
        $newbasic =  floatval($d['basic']);
        $salary = json_decode($d['edata']->salary, true);
        $profile = $d['edata']->profile;
        $structure = json_decode($d['edata']->structure);
        $tax_config = json_decode($d['edata']->tax_config);
        $index = $d['index'];

        for($i=$index;$i<12;$i++){
            $salary[$i]['basic'] = $newbasic * $salary[$i]['fraction'];
            for($k=0;$k<count($structure);$k++){
                if($structure[$k]->value == 0) continue;
                $param_name = $structure[$k]->param_name; 
                $salary[$i][$param_name] = $structure[$k]->value * $newbasic * $salary[$i]['fraction'] /100;
            }
        }
        $newtaxconfig = $this->tax_change_preparation($salary, $profile);
        $taxdiff = $newtaxconfig['finalTax'] - $tax_config->finalTax;
        $taxdelta = $taxdiff/(12-$index);
        for($i=$index;$i<12;$i++){
            $salary[$i]['monthly_tax'] += $taxdelta; 
        }
        $d['edata']->tax_config = json_encode($newtaxconfig);
        $d['edata']->salary = json_encode($newtaxconfig);
        return(['salary'=>$salary, 'edata'=>$d['edata']]);
    }
    public function upload(Request $request)
    {
        $dataarray = array();
        $uploadedFile = $request->fileToUpload;
        $filename = '_'.time().'.csv';

        Storage::disk('local')->put($filename, $uploadedFile);
        $changes = $this->filevalidate($filename);
        if($changes['status'] == 'success'){
            $response = array();
            $table = "yearly_income_".$request->fromYear."_".$request->toYear;
            $tax_changer = $this->taxable_income_changers();
            $tax_change_flag = false;
            if($request->month > 6)
                $index = $request->month - 7;
            else 
                $index = $request->month + 5;
            
            for($i=0;$i<count($changes['data']);$i++){
                $e = DB::table($table)->where('name', $changes['data'][$i]['employee_id'])->first();
                $s = json_decode($e->salary, true);
                foreach($tax_changer['yearchanger'] as $key=>$value){
                    if(isset($changes['data'][$i][$key])){
                        if($key == 'basic'){
                            $t = $this->basicChanger(['edata'=>$e, 'basic'=>$changes['data'][$i][$key], 'index'=>$index]);
                            $s = $t['salary'];
                            $e = $t['edata'];
                            $this->yearly_income_table_data_update($changes['data'][$i]['employee_id'], $table, ['salary'=>$e->salary,'tax_config'=>$e->tax_config]);
                        }
                    }
                }
                foreach($changes['data'][$i] as $key=>$value){
                    $s[$index][$key] = floatval($value);
                    if(isset($tax_changer['monthchanger'][$key]))
                        $tax_change_flag = true;
                }
                if($tax_change_flag){
                    $oldtaxconfig = json_decode($e->tax_config);
                    $newtaxconfig = $this->tax_change_preparation($s, $e->profile);
                    $taxdiff = $newtaxconfig['finalTax'] - $oldtaxconfig->finalTax;
                    $s[$index]['monthly_tax'] += $taxdiff;
                    $updates = [
                        'salary' => json_encode($s),
                        'tax_config' => json_encode($newtaxconfig),
                    ];
                    $salaries[$i] = $s[$index];
                    $tax_change_flag = false;
                }
                else {
                    $updates = [
                        'salary' => json_encode($s),
                    ];
                    $salaries[$i] = $s[$index];
                }

                $this->yearly_income_table_data_update($changes['data'][$i]['employee_id'], $table, $updates);
                $salaries[$i] = $s[$index];
            }
            return response()->json($response);
        }
        else {
            return response()->json($response);
        }
    }

    private function filevalidate($filename){
        $contents = Storage::disk('local')->get($filename);
        $lines = explode("\r", $contents);
        $heads = explode(",", $lines[0]);
        $allowables = array(
            'Employee ID'=>'employee_id',
            'Basic'=>'basic',
            'Bonus'=>'bonus',
            'Extra'=> 'extra',
            'Less'=> 'less',
            'Fooding'=>'fooding',
        );
        foreach($heads as $index=>$head){
            if(!isset($allowables[$head])){
                $response['status'] = 'fail';
                $response['message'] = "$head could not be found. Please check configuration";
                return $response;
            }
            else $heads[$index] = $allowables[$head];
        }
        for($y=1;$y<count($lines);$y++){
            $tempdat = explode(",", $lines[$y]);
            
            for($i=0;$i<count($heads);$i++){
                $data[$y-1][$heads[$i]] = trim($tempdat[$i]);
            }
        }
        $response['status'] = 'success';
        $response['data'] = $data;
        return $response;
    }
    
    public function taxconfig($table, $name){
        $db = DB::table($table)->where('name', $name)->select('salary', 'tax_config')->first();
        
        $salary = json_decode($db->salary);
        $tax_config = json_decode($db->tax_config, true);
        $s = new TaxConfig($salary, $tax_config);
        return response()->json($s->summary());
    }
}