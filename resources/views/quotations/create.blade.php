@extends('layouts.app')
@section('content')
<div class='container-fluid'>
    <div class="row m-4">
    <!-- Left Hand Side Headers Start -->
        <div class='col-md-9'>
            <div class="d-flex justify-content-between align-items-center">
            Date : {{date("m-d-Y")}}
            </div>
            <div class="d-flex justify-content-between align-items-center my-4">
                <span>Ref : {{date("m-d-Y")}}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <strong>{{$project->client->organization}}</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span>{{$project->client->address}}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center my-4">
                <span>{{$project->project_name}}</span>
            </div>
        </div>
    <!-- Left Hand Side Headers Ends -->
        <div class='col-md-3 my-4'>
            <div class='row'>A/O : </div>
            <div class='row'>Challan : </div>
            <div class='row'>Gate Pass : </div>
        </div>
    </div>

    <div class='row mx-4'>
        <div class='col-md-12'>
            <div class="d-flex justify-content-between align-items-center">
                {{$project->user->fname}} {{$project->user->sname}}
            </div>
            <div class="d-flex justify-content-between align-items-center">
                {{$project->user->designation->name}}
            </div>
            <div class="d-flex justify-content-between align-items-center">
                {{$project->user->phone}}
            </div>
        </div>
    </div>
</div>
@endsection