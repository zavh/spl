@extends('layouts.app')

@section('content')
<div class="row" >
    <div class="col-md-4">
        <div class="card-deck">
            <div class="card shadow-sm">
                <div class="border-bottom">
                    <div class="col-md-12">
                        Basic: {{$salarystructure->basic}}
                    </div>
                </div>
                {{-- <a href="/salarystructures/{{$salarystructure->id}}/edit" class='small'>Edit Salary Structure</a>
                <a href="/salarystructures/{{$salarystructure->id}}" class='small'>Delete Salary Structure</a> --}}
            </div>
        </div>
    </div>
</div>

@endsection