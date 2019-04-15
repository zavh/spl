<?php
namespace App\Http\Presentations;
use Carbon\Carbon;

class TaxConfig{
    public $salary;
    public $summary;
    
    function __construct($ys){
        $this->salary = $ys; //ys = yearly salary, object collection of individual months
    }

    function summary(){
        $totaldata = $this->initTotal();
        $monthdata = array();
        $salary = $this->salary;
        for($i=0;$i<count($salary);$i++){
            $monthdata[$i] = new \stdClass;
            $monthdata[$i]->month = Carbon::parse($salary[$i]->month)->format("M-Y");
            $monthdata[$i]->basic = $this->cf($salary[$i]->basic * $salary[$i]->fraction);
            $monthdata[$i]->house_rent = $this->cf($salary[$i]->house_rent);
            $monthdata[$i]->conveyance = $this->cf($salary[$i]->conveyance);
            $monthdata[$i]->medical_allowance = $this->cf($salary[$i]->medical_allowance);
            $monthdata[$i]->pf_company = $this->cf($salary[$i]->pf_company);
            $monthdata[$i]->bonus = $this->cf($salary[$i]->bonus);
            $monthdata[$i]->extra = $this->cf($salary[$i]->extra);
            $monthdata[$i]->less = $this->cf($salary[$i]->less);
            $monthdata[$i]->tax = $this->cf($salary[$i]->monthly_tax);
            
            $totaldata->basic += $salary[$i]->basic * $salary[$i]->fraction;
            $totaldata->house_rent += $salary[$i]->house_rent;
            $totaldata->conveyance += $salary[$i]->conveyance;
            $totaldata->medical_allowance += $salary[$i]->medical_allowance;
            $totaldata->pf_company += $salary[$i]->pf_company;
            $totaldata->bonus += $salary[$i]->bonus;
            $totaldata->extra += $salary[$i]->extra;
            $totaldata->less += $salary[$i]->less;
            $totaldata->tax += $salary[$i]->monthly_tax;
        }
        foreach($totaldata as $key=>$value)
            $totaldata->$key = $this->cf($value);
        $summary = new \stdClass;
        $summary->totaldata = $totaldata;
        $summary->monthdata = $monthdata;
        $this->summary = $summary;
        return $this->summary;
    }

    private function initTotal(){
        $totaldata = new \stdClass;
        $totaldata->basic = 0;
        $totaldata->house_rent = 0;
        $totaldata->conveyance = 0;
        $totaldata->medical_allowance = 0;
        $totaldata->pf_company = 0;
        $totaldata->bonus = 0;
        $totaldata->extra = 0;
        $totaldata->less = 0;
        $totaldata->tax = 0;

        return $totaldata;
    }

    private function cf($value){
        return number_format($value, 2);
    }
}