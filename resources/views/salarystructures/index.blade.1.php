@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-6 col-lg-6">
            <div class="card mb-4 shadow-sm h-md-250">
                <div class=" mb-0 bg-white rounded">
                    <div class="media text-muted">
                        <div class="media-body small">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <strong class="text-dark pl-1 pt-1">List of Salary Structures</strong>
                                <span>
                                <a href="/salarystructures/config" class='small mx-2'>Configure Structure</a>
                                <a href="/salarystructures/create" class='small mx-2'>Add Structure</a>
                                </span>
                            </div>
                            @foreach($structures as $structure)
                            <div class="d-flex justify-content-between border-top">
                                <div class="pl-2"> 
                                    Structure Name: <strong class="text-primary">{{$structure->structurename}}</strong> 
                                </div>
                                <span class="mx-2">
                                    <a href="javascript:void(0)" onclick="showStructure('{{$structure->id}}')" class='badge-success badge padge-pill'>Show</a>
                                    @if(count($structure->users)>0)
                                    <a href="javascript:void(0)" onclick="alert('Structure assigned to users, cannot delete')" class='badge-secondary badge padge-pill'>Delete</a>
                                    @else
                                    <a href="javascript:void(0)" onclick="deleteSalaryStructure('{{$structure->structurename}}','{{$structure->id}}')" class='badge-danger badge padge-pill'>Delete</a>
                                    @endif
                                    <a href="/salarystructures/{{$structure->id}}/edit" class="badge-success badge padge-pill">Edit</a>
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6" id="salary_structure_details">
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/salarystructure.js') }}"></script>
<script src="{{ asset('js/deleteform.js') }}"></script>