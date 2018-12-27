@extends('layouts.app')

@section('content')
<div class="row" >
    <div class="col-md-4">
        <div class="card-deck">
            <div class="card shadow-sm">
                <div class="border-bottom">
                    <div class="col-md-12">
                        Structure Name: {{$salarystructure->structurename}}
                    </div>
                    <div class="col-md-12">
                        House Rent: {{$salarystructure->houserent}}
                    </div>
                    <div class="col-md-12">
                        Medical Allowance: {{$salarystructure->medicalallowance}}
                    </div>
                    <div class="col-md-12">
                        Conveyance: {{$salarystructure->conveyance}}
                    </div>
                    <div class="col-md-12">
                        Provident Fund(Company): {{$salarystructure->providentfundcompany}}
                    </div>
                    <div class="col-md-12">
                        Provident Fund(Self): {{$salarystructure->providentfundself}}
                    </div>
                </div>
                {{-- <a href="/salarystructures/{{$salarystructure->id}}/edit" class='small'>Edit Salary Structure</a>
                <a href="/salarystructures/{{$salarystructure->id}}" class='small'>Delete Salary Structure</a> --}}
            </div>
        </div>
    </div>
</div>

@endsection