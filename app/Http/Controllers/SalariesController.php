<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Salary;
use App\SalaryStructure;
use Carbon\Carbon;
// use App\SalaryStructure;
use App\User;
use Illuminate\Support\Facades\Storage;

class SalariesController extends Controller
{

    public function index()
    {        
        $users = User::where('active', 1)->get();
        
        $salary = array();
        $tabheads = array('Employee ID','Basic');
        $flag = 0;
        $count = 0;
        $sp_update_date=array();
        $sp=0;
        $lastmonth_month = Carbon::now()->subMonth()->month;
        $lastmonth_year = Carbon::now()->subMonth()->year;
        $lastmonth = $lastmonth_month."_".$lastmonth_year;

        $salarystructure_update_flag = 0;

        // dd(count($salaries));
        $salaryprofile_updated = SalaryStructure::all()->all();
        
        if(!Schema::hasTable('salaryupdate_'.$lastmonth))
        {
            echo "no temp table                 ";
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
                $sp_update_date[$sp]['structureid'] =  $spupdate->id;
                $sp_update_date[$sp]['name'] = $spupdate->structurename;
                $sp_update_date[$sp]['update'] = $spupdate->updated_at;
                DB::table('salaryupdate_'.$lastmonth)->insert([
                    'structureid' => $sp_update_date[$sp]['structureid'],
                    'name' => $sp_update_date[$sp]['name'],
                    'update' => $sp_update_date[$sp]['update']
                ]);
            } 
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
                        $salarystructure_update_flag = 1; //switch flag

                        DB::table('salaryupdate_'.$lastmonth)->truncate(); //truncate table

                        $refreshprofiles = SalaryStructure::all()->all();
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
                    else
                    {
                        echo "date not updated                 ";
                    }
                }
            }
            else //profile updated
            {
                echo "new profile added                 ";
                $salarystructure_update_flag = 1; //switch flag

                DB::table('salaryupdate_'.$lastmonth)->truncate(); //truncate table

                $refreshprofiles = SalaryStructure::all()->all();
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
        }
        $salaryprofile_current = DB::table('salaryupdate_'.$lastmonth)->get();
        $salarydata = array();
        $i=0;
        
        $lm_salaries = DB::table('salary_'.$lastmonth)->get();
        
        if (!Schema::hasTable('salary_'.$lastmonth) || count($users)!= count($lm_salaries) || $salarystructure_update_flag == 1)
        {
            echo "update required                 ";
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
                        $salary[$count][$breakdown->param_name] = ($salary[$count]['basic'] * $breakdown->value)/100;
                        $profile[$breakdown->param_name] = $breakdown->value;
                    }
                    // $salary[$count]['salary_profile'] = $this->salaryProfile($si,$profile);
                    
                    $salary[$count]['gross_salary'] = $salary[$count]['basic']+
                                                    $salary[$count]['house_rent']+
                                                    $salary[$count]['medical_allowance']+
                                                    $salary[$count]['conveyance']+
                                                    $salary[$count]['extra'];
                    $tabheads['gross_salary'] = "Gross Salary";
                    
                    $salary[$count]['gross_total'] = $salary[$count]['gross_salary']+
                                                    $salary[$count]['pf_company'];
                    $tabheads['gross_total'] = "Gross Total";

                    $salary[$count]['tds'] = ceil(($this->salaryProfile($si,$profile, 0)['finalTax'])/12);
                    // $tabheads['tds'] = "Monthly Tax";

                    $salary[$count]['deduction_total'] = $salary[$count]['higher_purchase']+
                                                        $salary[$count]['loan']+
                                                        $salary[$count]['less']+
                                                        $salary[$count]['tds']+
                                                        $salary[$count]['fooding'];
                    $tabheads['deduction_total'] = "Deduction Total";

                    $salary[$count]['net_salary'] = $salary[$count]['gross_salary']-
                                                        $salary[$count]['deduction_total'];
                    $tabheads['net_salary'] = "Net Salary";
                    $flag = 1;
                    $count++;
                }
            }
            //////////////////////////////////////////////////////////////////////////////////////////
            // dd($tabheads);
            $salarydata = array();
            $i=0;
            
            $lastmonth_month = Carbon::now()->subMonth()->month;
            $lastmonth_year = Carbon::now()->subMonth()->year;
            $lastmonth = $lastmonth_month."_".$lastmonth_year;
            if (!Schema::hasTable('salary_'.$lastmonth))
            {
                Schema::connection('mysql')->create('salary_'.$lastmonth, function($table)
                {
                    $table->increments('id');
                    $table->integer('user_id')->nullable()->default(0);
                    $table->json('salarydata')->nullable()->default('');
                    $table->timestamps();
                });
            }
            else{
                DB::table('salary_'.$lastmonth)->truncate();
            }
            
            foreach ($salary as $sal) {
                $userinfo[$i] = User::where('name','=',$sal['name'])->get()->all();
                $id[$i] = $userinfo[$i][0]->id;
                $salarydata[$i] = json_encode($sal);

                DB::table('salary_'.$lastmonth)->insert([
                    'user_id' => $id[$i],
                    'salarydata' => $salarydata[$i]
                ]);

                // $saldata->user_id = $id;
                // $saldata->salarydata =  $salarydata[$i];//salarydata is indexed array  of the rows of the displayed table, json encoded
                // $saldata->save();
                $i++;
            }
        }
        else
        {
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
            //////////////////////////////////////////////////////////////////////////////////////////
            $x=0;
            echo "update not required                 ";
            $data = DB::table('salary_'.$lastmonth)->select('user_id','salarydata')->get()->all();
            $salary[0] = $tabheads;
            foreach($data as $value)
            {
                $salary[$x] = json_decode($value->salarydata,true);
                $x++;
            }
            // foreach($users as $index=>$user)
            // {
                
            // }
        }
        // dd($dta);
        // dd();
        /////////////////////////////////////////////////////////////////////////////////////////////
       return view('salaries.index',['salaries'=>$salary, 'heads'=>$tabheads]);
    }

    public function taxtable($id)
    {
        $user = User::find($id);

        $si = json_decode($user->salary->salaryinfo);
        $sstructure = $user->salarystructure;
        $ss = json_decode($sstructure->structure);

        
        foreach($ss as $breakdown){
            $profile[$breakdown->param_name] = $breakdown->value;
        }
        $r = $this::salaryProfile($si,$profile, 1);//response


        
        // dd($r);

        return view('salaries.taxtable',['response'=>$r['tds'],'dataarray'=>$r['da']]);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function upload(Request $request)
    {
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
        // dd($data);
        $updates = DB::table('salary_'.$lastmonth)->get()->all();
        foreach($data as $update)
        {
            // dd($update['Employee ID']);
            //  'select * from '."'salary_'.$lastmonth'".
            //         ' where JSON_CONTAINS(salarydata,name)';
            $query = 'JSON_CONTAINS(`salarydata`, \'{\"name\":\"'.$update['Employee ID'].'\"}\')';
            $name = DB::table('salary_'.$lastmonth)->whereRaw($query)->get()->first();
            $salarydata = json_decode($name->salarydata,true);
            // dd($salarydata,$update);
            $salarydata['higher_purchase'] = (int)$update['Hire Purchase'];
            $salarydata['loan'] = (int)$update['Loan'];
            $salarydata['less'] = (int)$update['Less'];
            $salarydata['fooding'] = (int)$update['Fooding'];
            $salarydata['extra'] = (int)$update['Extra'];
            $salarydata['deduction_total'] = (int)$salarydata['tds'] +
                                            (int)$update['Hire Purchase'] + 
                                            (int)$update['Loan'] + 
                                            (int)$update['Less'] + 
                                            (int)$update['Fooding'] + 
                                            (int)$update['Extra'];
            $salarydata['net_salary'] = (int)$salarydata['gross_salary']-
                                            (int)$salarydata['deduction_total'];
            $salarydata = json_encode($salarydata,true);
            //want to save the data again---- HOW???
            DB::table('salary_'.$lastmonth)->whereRaw($query)->update(['salarydata'=> $salarydata]);
            // $name = DB::table('salary_'.$lastmonth)->whereRaw($query)->get();
            // echo json_encode($name);
        }
        return back();
    }

    public function destroy($id)
    {

    }

    private function salaryProfile($user, $ss, $flag)
    {
        // dd($user);
        $ss = (object)$ss;
        $dataarray = array(
            "gender"=>$user->gender,
            "dob"=>$user->date_of_birth, 
            // 'age'=>30,
            "basicSalary"=>$user->basic,
            "perc_conveyance"=>$ss->conveyance,
            "perc_medical"=>$ss->medical_allowance, 
            "perc_houserent"=>$ss->house_rent,
            "extra"=>$ss->extra,
            "perc_pfcomp"=>$ss->pf_company, 
            "bonus"=>150000
        );
        // dd($dataarray);
        if($flag == 0){
            $response = $this::TDS($dataarray);
            return $response;
        }
        else if($flag == 1){
            $response['tds'] = $this::TDS($dataarray);
            $response['da'] = $dataarray;
            return $response;
        }
    }

    private function TDS($dataarray)
    {
        $bonus = $dataarray['bonus'];
        $response['bonus'] = $bonus;

        $gender = $dataarray['gender'];
        $response['gender'] = $gender;

        $age = Carbon::parse($dataarray['dob'])->age;
        $response['age'] = $age;

        $basicSalary = $dataarray['basicSalary'];
        $response['basicSalary'] = $basicSalary;

        $conveyance = floor($basicSalary * ($dataarray['perc_conveyance']/100));
        $response['conveyance'] = $conveyance;
        
        $medicalAllowance = floor($basicSalary * ($dataarray['perc_medical']/100));
        $response['medicalAllowance'] = $medicalAllowance;

        $houseRent = floor($basicSalary * ($dataarray['perc_houserent']/100));
        // $houseRent = 300000;
        $response['houseRent'] = floor($houseRent);

        $extra = $dataarray['extra'];
        $response['extra'] = $extra;

        $grossSalary = $basicSalary + $conveyance + $medicalAllowance + $houseRent;
        $response['grossSalary'] = $grossSalary;

        // $pfCompany = floor($basicSalary * ($dataarray['perc_pfcomp']/100));
        $pfCompany = 0;
        $response['pfCompany'] = $pfCompany;

        $grossTotal = $grossSalary +$pfCompany;
        $response['grossTotal'] = $grossTotal;

        // $pfSelf = floor($basicSalary * ($dataarray['perc_pfself']/100));
        // $response['pfSelf'] = floor($pfSelf);

        // $higherPurchase = $dataarray['higherPurchase'];
        // $response['higherPurchase'] = $higherPurchase;

        // $loan = $dataarray['loan'];
        // $response['loan'] = $loan;

        // $less = $dataarray['less'];
        // $response['less'] = $less;

        // $fooding = $dataarray['fooding'];
        // $response['fooding'] = $fooding;
        $houseRentExempted = 300000;
        $response['houseRentExempted'] = $houseRentExempted;

        $HouseRentTR = $houseRent*12;
        if(($HouseRentTR-$houseRentExempted)>=0)$HouseRentTR=$HouseRentTR-$houseRentExempted; else $HouseRentTR=0;
        $response['HouseRentTR'] = $HouseRentTR;
        // $response['HouseRentTR'] = 300000;
        
        $conveyanceExempted = 30000;
        $response['conveyanceExempted'] = $conveyanceExempted;

        $conveyanceTR = $conveyance*12;
        if(($conveyanceTR-$conveyanceExempted)>=0)$conveyanceTR=$conveyanceTR-$conveyanceExempted; else $conveyanceTR=0;
        $response['conveyanceTR'] = $conveyanceTR;

        $medicalExempted = 120000;
        $response['medicalExempted'] = $medicalExempted;

        $medicalTR = $medicalAllowance*12;
        if(($medicalTR-$medicalExempted)>=0)$medicalTR=$medicalTR-$medicalExempted; else $medicalTR=0;
        $response['medicalTR'] = $medicalTR;

        $taxableFields = $HouseRentTR + $conveyanceTR + $medicalTR + $bonus + $extra + $pfCompany;
        $response['taxableFields'] = $taxableFields;

        $TaxableSalary = ($basicSalary*12) + $taxableFields;
        $response['TaxableSalary'] = $TaxableSalary;

        if( $gender=='m' && $age<65)
        {
            $a = 250000;
        }
        elseif($gender == 'f' || $age>=65)
        {
            $a = 300000;
        }
        // $response['TaxableAmount'] = $TaxableAmount;

        $taxableIncome = $TaxableSalary;
        $slab = array($a, 400000, 500000, 600000, 3000000);
        $slabperc = array(0, 10,15,20,25,30);
        $tax = array();
        $slabamount = array();

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

        $Tax = array_sum($tax);
        $response['Tax'] = $Tax;

        $TI1 = $TaxableSalary - ($pfCompany*12);
        $MaxInvestment = ceil($TI1 * (25/100));
        $response['MaxInvestment'] = $MaxInvestment;
        $TIRebate = ceil($MaxInvestment * (15/100));
        $response['TIRebate'] = $TIRebate;
        $finalTax = $Tax - $TIRebate;
        $response['finalTax'] = $finalTax;

    return $response;

    }
}
