@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card-deck text-center">
        <!-- Unllocated Project Section Starts -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header text-white bg-danger m-0 p-0">
                    <span class="my-0 font-weight-normal">Unassigned Projects</span>
                    <span style='position:absolute;right:10px' class='small'>
                    <a href="/projects/create" style='color:white'>Create</a>
                    </span>
                </div>
                <!-- Body -->
                <div class="card-body p-0 m-0 small">

                    @foreach($punalloc as $project)
                    <div class="border-bottom">
                        <div class='text-left bg-light border-bottom'>
                            <span class="mx-2">Client: {{$project->client->organization}}</span>
                        </div>
                        <p class='text-left mx-4 mb-0 pb-0'>
                            Project title: <a href="/projects/{{$project->id}}">{{$project->project_name}}</a>
                        </p>
                        <div class='border-top text-left mx-4'>
                                Allocation: {{$project->allocation}}%
                        </div>
                    </div>
                    @endforeach

                </div>
            <!-- Body -->
            </div>
        <!-- Unllocated Project Section Ends -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Open Projects</h4>
                </div>
                <div class="card-body">
                    <h5 class="card-title pricing-card-title">Total Open: <small class="text-muted">{{count($projects)}}</small></h5>
                    <table class='table'>
                    <thead>
                        <tr>
                            <th scope="col">Client</th>
                            <th scope="col">Manager</th>
                            <th scope="col">View</th>
                        </tr>
                    <thead>
                    @foreach($projects as $project)
                        <tr>
                            <td><a href="/projects/{{$project->id}}">{{$project->project_name}}</a></td>
                            <td>{{$project->description}}</td>
                            <td><a href="/projects/{{$project->id}}/edit" class="btn btn-primary">Edit</a></td>
                        </tr>
                    @endforeach
                    </table>
                    <button type="button" class="btn btn-sm btn-block btn-primary">Show All</button>
                </div>
            </div>
            <div class="card mb-6 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Closed Projects</h4>
                </div>
            
                <div class='card mb-4 shadow-sm'>

                    <!-- Current month reports Starts -->
                        {{-- @if($current_month_report != null) --}}
                        <div class='row' id="day-wise">
                            {{-- @include('projects.showprojectlist',['current_month_report'=>$current_month_report]) --}}
                        </div>
                        {{-- @endif --}}
                    <!-- Current month reports Ends -->
                    
                    <!-- Report Search Area Starts-->
                    <div class="card mb-4 shadow-sm h-md-250">
                        <div class=" mb-0 bg-white rounded">
                            <div class="media text-muted">
                                <div class="media-body small">
                                    <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                                        <strong class="text-dark pl-1 pt-1">Search Projects</strong>
                                    </div>
                                    <div class="align-items-center text-black-50">
                                        <div class='row'>
                                            <div class="col-md-12">
                                                <form action="" class='m-1 p-1' autocomplete="off" id="findreports" name='findreports' onsubmit='findProjects(event, this)'>
                                                    <div class="input-group input-group-sm mb-1">
                                                        <strong class="col-md-4">Search by Date:</strong>
                                                        <div class="input-group input-group-sm row-md-6 col-md-4">
                                                            <input type="date" name='projectmonthstart' id='projectmonthstart' class="ploinput form-control"
                                                            placeholder="Start Date" onchange="dateSearchCriteria(this,1)">
            
                                                        </div>
            
                                                            <span class="invalid-feedback" role="alert" id="projectmonthstart_error_span">
                                                                <strong id="projectmonthstart_error"></strong>
                                                            </span>
            
                                                        <div class="input-group input-group-sm row-md-6 col-md-4">
                                                            <input type="date" class="form-control" value="" placeholder="End Date" id='dummyprojectmonthend' onchange="dateSearchCriteria(this,0)">
                                                        <input type="hidden" name='projectmonthend' id='projectmonthend' class="ploinput form-control">
                                                        </div>
            
                                                            <span class="invalid-feedback" role="alert" id="projectmonthend_error_span">
                                                                <strong id="projectmonthend_error"></strong>
                                                            </span>
                                                    </div>
            
                                                    <div class="input-group input-group-sm">
                                                        <strong class="col-md-4">Search by Client:</strong>
                                                        <input type="text" name='projectclient' id='projectclient' class="ploinput form-control" value=""
                                                            placeholder="Search Project" aria-label="Project Client" aria-describedby="button-addon2">
                                                        
                                                    </div>
                                                        <span class="invalid-feedback" role="alert" id="projectclient_error_span">
                                                            <strong id="projectclient_error"></strong>
                                                        </span>
            
                                                        
                                                    <div class="input-group input-group-sm">
                                                        <strong class="col-md-4">Search by Manager:</strong>
                                                        <input type="text" name='projectmanager' id='projectmanager' class="ploinput form-control" value=""
                                                            placeholder="Search Project" aria-label="Project Manager" aria-describedby="button-addon2">
                                                                
                                                    </div>
                                                        <span class="invalid-feedback" role="alert" id="projectmanager_error_span">
                                                            <strong id="projectmanager_error"></strong>
                                                        </span>
                                                    
                                                        <div class="input-group-append">
                                                        <button class="btn btn-secondary btn-sm" type="submit" id="button-addon2">Go</button>
                                                    </div>
                                                    {{-- <span class="invalid-feedback" role="alert" id="noinput_error_span">
                                                        <strong id="noinput_error"></strong>
                                                    </span> --}}
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Report Search Area Ends-->
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>
<script src="{{ asset('js/projects.js') }}"></script>
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>
          {{-- <div class="card-body">
            <h5 class="card-title pricing-card-title">Recently Closed</h5>
            <table class='table' style='font-size:12px'>
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Client</th>
                    <th scope="col">Status</th>
                    <th scope="col">View</th>
                </tr>
                <tr>
                        <td></td><td></td><td></td><td></td>
                </tr>
            </thead>
            </table>
            
            <p>
                Date: <input type='radio' name='search_criteria' value='Date Range'>
                Client: <input type='radio' name='search_criteria' value='Client Name'>
                Project Manager: <input type='radio' name='search_criteria' value='Project Manager'>
            </p>
            <p>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </p>            
            <button type="button" class="btn btn-sm btn-block btn-primary">Search</button>
          </div> --}}
        

