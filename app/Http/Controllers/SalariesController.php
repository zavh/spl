<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Salary;
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

                $salary[$count]['tds'] = ceil(($this->salaryProfile($si,$profile)['finalTax'])/12);
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
        // dd($salary);
       return view('salaries.index',['salaries'=>$salary, 'heads'=>$tabheads]);
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
    }

    public function destroy($id)
    {

    }

    private function salaryProfile($user, $ss)
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
        $response = $this::TDS($dataarray);
        return $response;
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

        $pfCompany = floor($basicSalary * ($dataarray['perc_pfcomp']/100));
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

        $taxRebate = $HouseRentTR + $conveyanceTR + $medicalTR + $bonus + $extra;
        $response['taxRebate'] = $taxRebate;

        $TaxableSalary = ($basicSalary*12) + $taxRebate;
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
