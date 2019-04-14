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
            $year = Carbon::parse($today)->subMonth()->format("Y-n");       //subMonth choses the previous month
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
                    $this->yearly_income_table_data_entry($d[$i]['profile']->id,$d[$i]['profile']->employee_id,$d[$i],$db_table_name);
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
        for($i=0;$i<count($d);$i++){
            if(Carbon::parse($d[$i]->profile->join_date)->gt($target_month)) continue;
            $response['data'][$i]['employee_id'] = $d[$i]->profile->employee_id;
            $response['data'][$i]['name'] = $d[$i]->profile->name;
            $response['data'][$i]['join_date'] = $d[$i]->profile->join_date;
            $response['data'][$i]['basic'] = $d[$i]->profile->basic;
            $response['data'][$i]['house_rent'] = number_format($d[$i]->salary[$month]->house_rent, 2);
            $response['data'][$i]['conveyance'] = number_format($d[$i]->salary[$month]->conveyance, 2);
            $response['data'][$i]['medical_allowance'] = number_format($d[$i]->salary[$month]->medical_allowance, 2);
            $response['data'][$i]['pf_self'] = number_format($d[$i]->salary[$month]->pf_self, 2);
            $response['data'][$i]['pf_company'] = number_format($d[$i]->salary[$month]->pf_company, 2);
            $response['data'][$i]['bonus'] = number_format($d[$i]->salary[$month]->bonus, 2);
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
        $tabheads = array(
            'basic'=> 'Basic',
            'bonus'=> 'Bonus',
            'extra'=> 'Extra',
            'less'=> 'Less',
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
    public function upload(Request $request)
    {
        $dataarray = array();
        $uploadedFile = $request->fileToUpload;
        $filename = '_'.time().'.csv';

        Storage::disk('local')->put($filename, $uploadedFile);
        $changes = $this->filevalidate($filename);
        if($changes['status'] == 'success'){
            $table = "yearly_income_".$request->fromYear."_".$request->toYear;
            $tax_changer = $this->taxable_income_changers();
            $tax_change_flag = false;
            if($request->month >6)
                $index = $request->month - 7;
            else 
            $index = $request->month + 5;
            
            for($i=0;$i<count($changes);$i++){
                $e = DB::table($table)->where('name', $changes['data'][$i]['employee_id'])->first();
                $s = json_decode($e->salary, true);
                foreach($changes['data'][$i] as $key=>$value){
                    $s[$index][$key] = floatval($value);
                    if(isset($tax_changer[$key]))
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
            return response()->json($salaries);
            // return response()->json($changer);
        }
        else {
            return response()->json($changes);
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
        $s = new \stdClass;
        //$s->monthdata = new \stdClass;
        $s->totaldata = new \stdClass;
        $s->totaldata->basic = 0;
        $s->totaldata->house_rent = 0;
        $s->totaldata->conveyance = 0;
        $s->totaldata->medical_allowance = 0;
        $s->totaldata->pf_company = 0;
        $s->totaldata->bonus = 0;
        $s->totaldata->extra = 0;
        $s->totaldata->less = 0;
        $s->totaldata->tax = 0;
        for($i=0;$i<count($salary);$i++){
            $s->monthdata[$i] = new \stdClass;
            $s->monthdata[$i]->month = Carbon::parse($salary[$i]->month)->format("M-Y");
            $s->monthdata[$i]->basic = $this->cf($salary[$i]->basic * $salary[$i]->fraction);
            $s->monthdata[$i]->house_rent = $this->cf($salary[$i]->house_rent);
            $s->monthdata[$i]->conveyance = $this->cf($salary[$i]->conveyance);
            $s->monthdata[$i]->medical_allowance = $this->cf($salary[$i]->medical_allowance);
            $s->monthdata[$i]->pf_company = $this->cf($salary[$i]->pf_company);
            $s->monthdata[$i]->bonus = $this->cf($salary[$i]->bonus);
            $s->monthdata[$i]->extra = $this->cf($salary[$i]->extra);
            $s->monthdata[$i]->less = $this->cf($salary[$i]->less);
            $s->monthdata[$i]->tax = $this->cf($salary[$i]->monthly_tax);
            
            $s->totaldata->basic += $salary[$i]->basic * $salary[$i]->fraction;
            $s->totaldata->house_rent += $salary[$i]->house_rent;
            $s->totaldata->conveyance += $salary[$i]->conveyance;
            $s->totaldata->medical_allowance += $salary[$i]->medical_allowance;
            $s->totaldata->pf_company += $salary[$i]->pf_company;
            $s->totaldata->bonus += $salary[$i]->bonus;
            $s->totaldata->extra += $salary[$i]->extra;
            $s->totaldata->less += $salary[$i]->less;
            $s->totaldata->tax += $salary[$i]->monthly_tax;
        }
        foreach($s->totaldata as $key=>$value){
            $s->totaldata->$key = $this->cf($value);
        }
        return response()->json($s);
    }
    private function cf($value){
        return number_format($value, 2);
    }
}