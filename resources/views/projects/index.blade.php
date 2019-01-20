@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class='row'>
        <!-- Open Project Section Starts -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-primary m-0 p-0">
                    <span class="mx-2 font-weight-normal">Project Status : Open</span>
                    <span style='position:absolute;right:10px' class='small'>
                    <a href="/projects/create" style='color:white'>Create</a>
                    </span>
                </div>
                <!-- Body -->
                <div class="card-body p-0 m-0 small">
                    @foreach($open as $project)
                    <div class="">
                        <div class='text-left bg-light border-bottom border-top'>
                            <span class="mx-2">Client: {{$project->client->organization}}</span>
                        </div>
                        <p class='text-left mx-4 mb-0 pb-0'>
                            Project title: <a href="/projects/{{$project->id}}">{{$project->project_name}}</a>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center border-top mb-0 mx-4">
                            <span>Allocation: {{$project->allocation}}%</span>
                            <span>Completion: {{$project->completed}}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- Body -->
            </div>
        </div>
        <!-- Open Project Section Ends -->
        <!-- Expired Project Section Starts -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-danger m-0 p-0">
                    <span class="mx-2 font-weight-normal">Project Status : Expired</span>
                </div>
                <!-- Body -->
                <div class="card-body p-0 m-0 small">
                @if(count($expired) == 0)
                <span class='mx-4'>No expired Project found</span>
                @else
                @foreach ($expired as $i=>$project)
                <div id='contact-item-{{$i}}'>
                    <div class="media text-muted" id="heading{{$i}}">
                        <div class="media-body pb-0 mb-0 small lh-125">
                            <div class="d-flex justify-content-between align-items-center w-100 border-bottom mb-0 pb-0 pr-2 bg-light">
                                <a href="/projects/{{$project->id}}" class='mx-2'>{{$project->project_name}}</a>
                            </div>
                        </div>
                    </div>
                </div> 
                @endforeach
                @endif
                </div>
                <!-- Body -->
            </div>
        </div>
        <!-- Expired Project Section Ends -->
        <!-- Expired Project Section Starts -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-success m-0 p-0">
                    <span class="mx-2 font-weight-normal">Project Status : Closed</span>
                </div>
                <!-- Body -->
                <div class="card-body p-0 m-0">
                @if(count($closed)>0)
                    <div class='row' id="closed">
                        @include('projects.showprojectlist',['searched_project'=>$closed,'title'=>'Project List: last 5 closed projects shown'])
                    </div>
                @endif
                    <form action="" class='m-1 p-1 small' autocomplete="off" id="findreports" name='findreports' onsubmit='findProjects(event, this)'>
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
                    </form>
                </div>
                <!-- Body -->
            </div>
        </div>
        <!-- Expired Project Section Ends -->
    </div>
</div>
@endsection
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>
<script src="{{ asset('js/projects.js') }}"></script>
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>