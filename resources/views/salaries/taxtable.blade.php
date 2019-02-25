@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row" >
            <div class="col-lg-12">
                <div class="card mb-4 shadow-sm h-md-250">
				<div class=" mb-0 bg-white rounded">
					<div class="media text-muted">
						<div class="media-body small">
                            <strong class="d-inline-block mb-2 text-primary" style="font-size:14pt" >Tax Table</strong>                           
                            <div class="bg-light border-bottom w-100">
                                <table class="table table table-bordered table-striped">
                                    <tr>
                                        <th class="text-center"><strong> Head of Income</strong></th>
                                        <th class="text-center"><strong> Monthly</strong></th>
                                        <th class="text-center"><strong>x</strong></th>
                                        <th class="text-center"><strong>Month</strong></th>
                                        <th class="text-center"><strong>Actual</strong></th>
                                        <th class="text-center"><strong>Exempted</strong></th>
                                        <th class="text-center"><strong> Taxable Income</strong></th>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Basic Pay</td>
                                        <td class="text-center">{{$response['basicSalary']}}</td>
                                        <td class="text-center">x</td>
                                        <td class="text-center">12</td>{{-- check how to put in, hardcode or calculate --}}
                                        <td class="text-center">{{$response['basicSalary']*12}}</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">{{$response['basicSalary']*12}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">House Rent-{{$dataarray['perc_houserent']}}%</td>
                                        <td class="text-center">{{$response['houseRent']}}</td>
                                        <td class="text-center">x</td>
                                        <td class="text-center">12</td>{{-- check how to put in, hardcode or calculate --}}
                                        <td class="text-center">{{$response['houseRent']*12}}</td>
                                        <td class="text-center">{{$response['houseRentExempted']}}</td>
                                        <td class="text-center">{{$response['HouseRentTR']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Medical Allowance-{{$dataarray['perc_medical']}}%</td>
                                        <td class="text-center">{{$response['medicalAllowance']}}</td>
                                        <td class="text-center">x</td>
                                        <td class="text-center">12</td>{{-- check how to put in, hardcode or calculate --}}
                                        <td class="text-center">{{$response['medicalAllowance']*12}}</td>
                                        <td class="text-center">{{$response['medicalExempted']}}</td>
                                        <td class="text-center">{{$response['medicalTR']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Conveyance-{{$dataarray['perc_conveyance']}}%</td>
                                        <td class="text-center">{{$response['conveyance']}}</td>
                                        <td class="text-center">x</td>
                                        <td class="text-center">12</td>{{-- check how to put in, hardcode or calculate --}}
                                        <td class="text-center">{{$response['conveyance']*12}}</td>
                                        <td class="text-center">{{$response['conveyanceExempted']}}</td>
                                        <td class="text-center">{{$response['conveyanceTR']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">P/F Company-{{$dataarray['perc_pfcomp']}}%</td>
                                        <td class="text-center">{{$response['pfCompany']}}</td>
                                        <td class="text-center">x</td>
                                        <td class="text-center">12</td>{{-- check how to put in, hardcode or calculate --}}
                                        <td class="text-center">{{$response['pfCompany']*12}}</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">{{$response['pfCompany']*12}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Bonus</td>
                                        <td class="text-center">{{$response['bonus']}}</td>
                                        <td class="text-center">x</td>
                                        <td class="text-center">2</td>{{-- check how to put in, hardcode or calculate --}}
                                        <td class="text-center">{{$response['bonus']*2}}</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">{{$response['bonus']*2}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Extra</td>
                                        <td class="text-center">{{$response['extra']}}</td>
                                        <td class="text-center">x</td>
                                        <td class="text-center">1</td>{{-- check how to put in, hardcode or calculate --}}
                                        <td class="text-center">{{$response['extra']}}</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">{{$response['extra']}}</td>
                                    </tr>
                                    <tr>
                                    <td class="text-right" colspan="6">Total Taxable Income</td>
                                        <td class="text-center" colspan="2">{{$response['TaxableSalary']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="6">Total Taxable Income without P/F income</td>
                                        <td class="text-center" colspan="2">{{$response['TaxableSalary']-($response['pfCompany']*12)}}</td>
                                    </tr>
                                </table>
                                <table class="table table table-bordered table-striped">
                                    <tr>
                                        <td class="text-center" colspan="6">Tax Calculation</td>
                                        <td class="text-center" colspan="2">Tax</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">First</td>
                                        <td class="text-center">{{$response['slab'][0]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">0%</td>
                                        <td class="text-center">{{$response['slabamount'][0]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{$response['slabwisetax'][0]}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Next</td>
                                        <td class="text-center">{{$response['slab'][1]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">10%</td>
                                        <td class="text-center">{{$response['slabamount'][1]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{$response['slabwisetax'][1]}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Next</td>
                                        <td class="text-center">{{$response['slab'][2]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">15%</td>
                                        <td class="text-center">{{$response['slabamount'][2]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{$response['slabwisetax'][2]}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Next</td>
                                        <td class="text-center">{{$response['slab'][3]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">20%</td>
                                        <td class="text-center">{{$response['slabamount'][3]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{$response['slabwisetax'][3]}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Next</td>
                                        <td class="text-center">{{$response['slab'][4]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">25%</td>
                                        <td class="text-center">{{$response['slabamount'][4]}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{$response['slabwisetax'][4]}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Balance</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">30%</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{$response['slabwisetax'][5]}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="4">Total Tax</td>
                                        <td class="text-center"colspan="1">{{$response['TaxableSalary']}}</td>
                                        <td class="text-center"colspan="1"></td>
                                        <td class="text-center">{{$response['Tax']}}</td>
                                    </tr>
                                    {{-- Tax rebate bujhi nai in format document --}}
                                    <tr>
                                        <td class="text-left" colspan="6">
                                            Less:Tax Rebate for Investment @ 15% of tk 
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{$response['TIRebate'] }} 
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            (25% of tk 
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            {{$response['MaxInvestment']}}</td>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <td class="text-center"colspan="1">{{$response['TIRebate']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="6">Total</td>
                                        <td class="text-center"colspan="1">{{$response['finalTax']}}</td>
                                    </tr>

                                    {{-- why? --}}
                                    <tr>
                                        <td class="text-right" colspan="6">80%</td>
                                        <td class="text-center"colspan="1">{{$response['finalTax']*(80/100)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="6">Deduction-Car or DPS or Other</td>
                                        <td class="text-center"colspan="1"> (-)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="6">Yearly Tax Payable</td>
                                        <td class="text-center"colspan="1">{{$response['finalTax']*(80/100)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right" colspan="6">Monthly Tax Payable</td>
                                        <td class="text-center"colspan="1">{{($response['finalTax']*(80/100))/12}}</td>
                                    </tr>
                                </table>

                                {{-- <pre>
                                    @php
                                    print_r($response);    
                                    @endphp
                                </pre> --}}
                            </div>                                          
                        </div>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection