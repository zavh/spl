<?php
namespace App\Http\Presentations;
use Carbon\Carbon;

class TaxConfig{
    private $salary;
    private $tax_config;
    public $summary;
    
    function __construct($ys, $tc){
        $this->salary = $ys; //ys = yearly salary, object collection of individual months
        $this->tax_config = $tc;
    }

    public function summary(){
        // $totaldata = $this->initTotal();
        $monthdata = array();
        $salary = $this->salary;
        for($i=0;$i<count($salary);$i++){
            $monthdata[$i] = new \stdClass;
            $monthdata[$i]->month = Carbon::parse($salary[$i]->month)->format("M-Y");
            $monthdata[$i]->basic = $this->cf($salary[$i]->basic);
            $monthdata[$i]->house_rent = $this->cf($salary[$i]->house_rent);
            $monthdata[$i]->conveyance = $this->cf($salary[$i]->conveyance);
            $monthdata[$i]->medical_allowance = $this->cf($salary[$i]->medical_allowance);
            $monthdata[$i]->pf_company = $this->cf($salary[$i]->pf_company);
            $monthdata[$i]->bonus = $this->cf($salary[$i]->bonus);
            $monthdata[$i]->extra = $this->cf($salary[$i]->extra);
            $monthdata[$i]->less = $this->cf($salary[$i]->less);
            $monthdata[$i]->tax = $this->cf($salary[$i]->monthly_tax);
        }
        
        $totaldata = new \stdClass;
        $totaldata->basic = $this->cf($this->tax_config['basic']);
        $totaldata->house_rent = $this->cf($this->tax_config['house_rent']);
        $totaldata->conveyance = $this->cf($this->tax_config['conveyance']);
        $totaldata->medical_allowance = $this->cf($this->tax_config['medical_allowance']);
        $totaldata->pf_company = $this->cf($this->tax_config['pf_company']);
        $totaldata->bonus = $this->cf($this->tax_config['bonus']);
        $totaldata->extra = $this->cf($this->tax_config['extra']);
        $totaldata->less = $this->cf($this->tax_config['less']);
        $totaldata->tax = $this->cf($this->tax_config['finalTax']);

        $summary = new \stdClass;
        $summary->totaldata = $totaldata;
        $summary->monthdata = $monthdata;
        $summary->taxable = $this->getTaxableIncomeTable($totaldata, $this->tax_config);
        $summary->taxable_salary = $this->cf($this->tax_config['taxable_salary']);
        $summary->slabinfo = $this->getSlabTable($this->tax_config['slabinfo']);
        $summary->taxbeforeinv = $this->cf($this->tax_config['taxbeforeinv']);
        $summary->MaxInvestment = $this->cf($this->tax_config['MaxInvestment']);
        $summary->TIRebate = $this->cf($this->tax_config['TIRebate']);
        $summary->finalTax = $this->cf($this->tax_config['finalTax']);
        $this->summary = $summary;
        return $this->summary;
    }

    private function getTaxableIncomeTable($total, $taxconfig){
        $taxableIncome = array();
        foreach($total as $key=>$value){
            if($key == 'tax') continue;
            $taxableIncome[$key]['actual']=$this->cf($taxconfig[$key]);
            if(isset($taxconfig[$key.'_exempted']))
                $taxableIncome[$key]['exempted']=$this->cf($taxconfig[$key.'_exempted']);
            else 
                $taxableIncome[$key]['exempted']=0;
            if(isset($taxconfig[$key.'_tax_remaining']))
                $taxableIncome[$key]['taxable']=$this->cf($taxconfig[$key.'_tax_remaining']);
            else 
                $taxableIncome[$key]['taxable']=$this->cf($taxconfig[$key]);
        }
        return $taxableIncome;
    }

    private function getSlabTable($slabinfo){
        $response = array();
        $y = 0;
        $slabamount =0;
        for($i=0;$i<count($slabinfo['slabperc']);$i++){
            $response[$i][$y++] = 'Slab - '.($i+1);
            if($i==(count($slabinfo['slabperc']) - 1))
                $response[$i][$y++] = 'Balance';
            else
                $response[$i][$y++] = $this->cf($slabinfo['slab'][$i]);
            $response[$i][$y++] = $slabinfo['slabperc'][$i];
            $response[$i][$y++] = $this->cf($slabinfo['slabamount'][$i]);
            $response[$i][$y++] = $this->cf($slabinfo['slabwisetax'][$i]);
            $y = 0;
        };

        return $response;
    }
    private function cf($value){
        return number_format($value, 2);
    }
}