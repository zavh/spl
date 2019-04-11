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
        //First determine the current payscale year. This will help to determine valid user input
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
        if($year == null){
            $fromYear = $currentFromYear;
            $toYear = $currentToYear;
            $year = Carbon::parse($today)->subMonth()->format("Y-n");
            $thisMonth = Carbon::parse($today)->subMonth()->format("n");
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

        if($thisMonth > 6 ){
            $month = $thisMonth - 7;
        }
        else {
            $month = 5 + $thisMonth;
        }
        $db_table_name = 'yearly_income_'.$fromYear."_".$toYear;
        if(Schema::hasTable($db_table_name)){
            // This is going to fetch tables from db and then process the presentation
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
                $db = $this->initial_salary_generator("$fromYear-07-01", "$toYear-06-30");
                // call yearly_income_table_generator and yearly_income_table_data_entry
                //$this->yearly_income_table_generator($db_table_name);

                // then send this reponse to prepare presentation
                $response = $this->presentation($db['salaries'], $year, $month);
                $response['status'] = 'success';
                $response['target_month'] = $year;

                // return response()->json($db['salaries']);
                return response()->json($response);
            }
        }
    }
    private function yearly_income_table_generator($table_name)//fix this to start from july instead of january
    {
        Schema::connection('mysql')->create($table_name, function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('user_name');
            $table->json('yearly_income');
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
            'yearly_income' => json_encode($yearlyProbableSalary)
        ]);
    }

    private function presentation($d, $year, $month){
        $response['tabheads'] = $this->tabhead_generation();
        $t = explode('-',$year);
        $target_month = Carbon::create($t[0],$t[1]+1, 1, 0, 0, 0, 'Asia/Dhaka');
        for($i=0;$i<count($d);$i++){
            if(Carbon::parse($d[$i]['profile']->join_date)->gt($target_month)) continue;
            $response['data'][$i]['employee_id'] = $d[$i]['profile']->employee_id;
            $response['data'][$i]['name'] = $d[$i]['profile']->name;
            $response['data'][$i]['join_date'] = $d[$i]['profile']->join_date;
            $response['data'][$i]['basic'] = $d[$i]['profile']->basic;
            $response['data'][$i]['house_rent'] = $d[$i]['salary'][$month]['house_rent'];
            $response['data'][$i]['conveyance'] = $d[$i]['salary'][$month]['conveyance'];
            $response['data'][$i]['medical_allowance'] = $d[$i]['salary'][$month]['medical_allowance'];
            $response['data'][$i]['pf_self'] = $d[$i]['salary'][$month]['pf_self'];
            $response['data'][$i]['pf_company'] = $d[$i]['salary'][$month]['pf_company'];
            $response['data'][$i]['bonus'] = $d[$i]['salary'][$month]['bonus'];
            if(is_array($d[$i]['salary'][$month]['loan']))
                $response['data'][$i]['loan'] = number_format($d[$i]['salary'][$month]['loan']['sum'],2);
            else 
                $response['data'][$i]['loan'] = $d[$i]['salary'][$month]['loan'];
            $response['data'][$i]['extra'] = $d[$i]['salary'][$month]['extra'];
            $response['data'][$i]['less'] = $d[$i]['salary'][$month]['less'];
            $response['data'][$i]['fooding'] = $d[$i]['salary'][$month]['fooding'];
            $response['data'][$i]['monthly_tax'] = number_format($d[$i]['salary'][$month]['monthly_tax'], 2);
            $deduction_total = 
                        $d[$i]['salary'][$month]['pf_self'] + 
                        $d[$i]['salary'][$month]['loan']['sum'] +
                        $d[$i]['salary'][$month]['less'] +
                        $d[$i]['salary'][$month]['monthly_tax'] +
                        $response['data'][$i]['fooding']
                        ;
            $response['data'][$i]['deduction_total'] = number_format($deduction_total, 2);
            $gross_salary = 
                        $d[$i]['profile']->basic * $d[$i]['salary'][$month]['fraction'] +
                        $d[$i]['salary'][$month]['house_rent'] +
                        $d[$i]['salary'][$month]['conveyance'] +
                        $d[$i]['salary'][$month]['medical_allowance'] +
                        $d[$i]['salary'][$month]['pf_self'] +
                        $d[$i]['salary'][$month]['bonus'] +
                        $d[$i]['salary'][$month]['extra']
                        ;
            $response['data'][$i]['gross_salary'] = number_format($gross_salary, 2);
            $response['data'][$i]['gross_total'] = number_format($gross_salary + $d[$i]['salary'][$month]['pf_company'], 2);
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
            $data['salaries'][$index] = $this->yearly_generator($user, $fromYear, $toYear);
            //Step 2: Generate tentative yearly tax
            $tax_config = $this->income_tax_calculation($data['salaries'][$index]);
            $data['salaries'][$index]['tax_config'] = $tax_config;
            //Step 3: Generate monthly tax
            $data['salaries'][$index] = $this->generate_monthly_tax($data['salaries'][$index], $tax_config['finalTax']);
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

        //$filename = $uploadedFile->getClientOriginalName();
        // $lastmonth_month = Carbon::now()->subMonth()->month;
        // $lastmonth_year = Carbon::now()->subMonth()->year;
        // $lastmonth = $lastmonth_month."_".$lastmonth_year;

        //Storage::disk('local')->putFileAs('files', $uploadedFile, $filename);
        Storage::disk('local')->put($filename, $uploadedFile);
        return response()->json($request->all());
        //$contents = Storage::get('/files/'.$filename);
        // $lines = explode("\n", $contents);
        // $heads = explode(",", $lines[0]);
        // for($y=1;$y<count($lines);$y++){
        //     $tempdat = explode(",", $lines[$y]);
            
        //     for($i=0;$i<count($heads);$i++){
        //         $data[$y-1][$heads[$i]] = $tempdat[$i];
        //     }
        // }
        
        // $updates = DB::table('salary_'.$lastmonth)->get()->all();
        // foreach($data as $update)
        // {
        //     $query = 'JSON_CONTAINS(`salarydata`, \'{\"name\":\"'.$update['Employee ID'].'\"}\')';
        //     $name = DB::table('salary_'.$lastmonth)->whereRaw($query)->get()->first();
        //     if(isset($name))
        //     {
        //         $salarydata = json_decode($name->salarydata,true);
                
        //         $salarydata['hire_purchase'] = (float)$update['Hire Purchase'];
        //         $salarydata['fooding'] = (float)$update['Fooding'];
        //         $user_id = User::where('name', $salarydata['name'])->get()->first()->id;

        //         $total_loan = $this->loantotal($user_id);

        //         $salarydata['loan'] = (float)$total_loan;
        //         $salarydata['bonus'] = (float)$update['Bonus'];
        //         $salarydata['extra'] = (float)$update['Extra'];
        //         $salarydata['less'] = (float)$update['Less'];
        //         $salarydata['deduction_total'] =(float)$salarydata['pf_self'] +
        //                                         (float)$salarydata['pf_company'] +
        //                                         (float)$update['Hire Purchase'] + 
        //                                         (float)$salarydata['tds'] +
        //                                         (float)$update['Loan'] + 
        //                                         (float)$update['Less'] + 
        //                                         (float)$update['Fooding'];
        //         $salarydata['net_salary'] = (float)$salarydata['gross_salary']-
        //                                         (float)$salarydata['deduction_total'];

        //         $dataarray_tds = $salarydata;

                
        //         $salary_info = json_decode((Salary::where('user_id',$user_id)->get()->first()->salaryinfo),true);
        //         $dataarray['gender'] = $salary_info['gender'];
        //         $dataarray['dob'] = $salary_info['date_of_birth'];
        //         //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //         $dataarray['user'] = User::find($user_id);
        //         //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //         $dataarray['basicSalary'] = $dataarray_tds['basic'];
        //         $dataarray['perc_conveyance'] = (float)(100 * ($dataarray_tds['conveyance']/$dataarray_tds['basic']));
        //         $dataarray['perc_medical'] = (float)(100 * ($dataarray_tds['medical_allowance']/$dataarray_tds['basic']));
        //         $dataarray['perc_houserent'] = (float)(100 * ($dataarray_tds['house_rent']/$dataarray_tds['basic']));
        //         $dataarray['perc_pfcomp'] = (float)(100 * ($dataarray_tds['pf_company']/$dataarray_tds['basic']));

                
        //         $sp = $this::yearly_generation($dataarray,$dataarray_tds['bonus'],$dataarray_tds['extra'],$dataarray_tds['less']);
        //         // dd($user_id, $total_loan);
                
        //         $dataarray_tds['gross_salary'] = $sp['response_without_addons']['grossSalary'];
        //         $dataarray_tds['gross_total'] = $sp['response_without_addons']['grossTotal'];
        //         $dataarray_tds['tds'] = $sp['response_without_addons']['finalTax']/12 + $sp['addons_tax'];
        //         $dataarray_tds['loan'] = $total_loan;

        //         DB::table('salary_'.$lastmonth)->whereRaw($query)->update(['salarydata'=> json_encode($dataarray_tds)]);
                
        //     }
        //     //////////////////////////regenerating the table////////////////////////////////////////////    
        //     $tabheads = $this->tabhead_generation();//generates the tabheads from salarystructure
        //     $salary = $this->salary_extraction_from_lastmonth_table($lastmonth);//extracts the salary from lastmonth and puts in array
        //     ////////////////////////////////////////////////////////////////////////////////////      
        //     return view('salaries.index',['salaries'=>$salary, 'heads'=>$tabheads]); 
        //     // return back();   
        //}      

    }
}
