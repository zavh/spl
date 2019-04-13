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
            if($thisMonth > 6){
                $fromYear = $targetPeriod[0] + 0;
                $toYear = $targetPeriod[0] + 1;
            }
            else{
                $fromYear = $targetPeriod[0] - 1;
                $toYear = $targetPeriod[0] + 0;
            }

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
        $response = $this->presentation( $d, $year, $month);
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
    
    private function yearly_income_table_data_update($id,$name,$yearlyProbableSalary,$year,$index)
    {
        $x = 0;
        $y2 = (string)((int)$year + 1);

        DB::table('yearly_income_'.$year.'_'.$y2)
        ->where('user_id',$id)
        ->update([
            'user_id' => $id,
            'name' => $name,
            'yearly_income' => json_encode($yearlyProbableSalary)
        ]);
    }

    private function presentation($d, $year, $month){
        $d = json_decode(json_encode($d));
        $response['tabheads'] = $this->tabhead_generation();
        $t = explode('-',$year);
        $target_month = Carbon::create($t[0],$t[1]+1, 1, 0, 0, 0, 'Asia/Dhaka');
        for($i=0;$i<count($d);$i++){
            if(Carbon::parse($d[$i]->profile->join_date)->gt($target_month)) continue;
            $response['data'][$i]['employee_id'] = $d[$i]->profile->employee_id;
            $response['data'][$i]['name'] = $d[$i]->profile->name;
            $response['data'][$i]['join_date'] = $d[$i]->profile->join_date;
            $response['data'][$i]['basic'] = $d[$i]->profile->basic;
            $response['data'][$i]['house_rent'] = $d[$i]->salary[$month]->house_rent;
            $response['data'][$i]['conveyance'] = $d[$i]->salary[$month]->conveyance;
            $response['data'][$i]['medical_allowance'] = $d[$i]->salary[$month]->medical_allowance;
            $response['data'][$i]['pf_self'] = $d[$i]->salary[$month]->pf_self;
            $response['data'][$i]['pf_company'] = $d[$i]->salary[$month]->pf_company;
            $response['data'][$i]['bonus'] = $d[$i]->salary[$month]->bonus;
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
                        $d[$i]->salary[$month]->pf_self +
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

    public function upload(Request $request)
    {
        $dataarray = array();
        $uploadedFile = $request->fileToUpload;
        $filename = '_'.time().'.csv';

        Storage::disk('local')->put($filename, $uploadedFile);
        $changes = $this->filevalidate($filename);
        if($changes['status'] == 'success'){
            $table = "yearly_income_".$request->fromYear."_".$request->toYear;
            if($request->month >6)
                $index = $request->month - 7;
            else 
            $index = $request->month + 5;
            
            for($i=0;$i<count($changes);$i++){
                $e = DB::table($table)->where('name', $changes['data'][$i]['employee_id'])->first();
                $s = json_decode($e->salary);
                foreach($changes['data'][$i] as $key=>$value)
                $s[$index]->$key = floatval($value);
                $salaries[$i] = $s[$index];
            }
            return response()->json($salaries);
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
    
}
