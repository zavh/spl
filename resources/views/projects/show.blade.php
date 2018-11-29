@extends('layouts.app')
@section('content')
    <main role="main" class="container-fluid">
        <div class='row'>
        <!-- Project Details Column Starts-->
            <div class='col-md-4'>
                <!-- Project Name Area Starts-->
                <div class="d-flex align-items-center p-2 my-1 text-white-50 bg-primary rounded shadow-sm">
                    <div class="lh-100">
                    <h6 class="mb-0 text-white lh-100"> <span class='text-white-50'>Project Name:</span> {{$project->project_name}}</h6>
                    <small>Posted on: {{$project->created_at}} by {{$project->user->name}}</small>
                    </div>
                </div>
                <!-- Project Name Area Ends-->
                <!-- Client Name Area Starts-->
                <div class="d-flex align-items-center p-2 my-1 text-white-50 bg-primary rounded shadow-sm">
                    <div class="lh-100">
                    <h6 class="mb-0 text-white lh-100"> <span class='text-white-50'>Client:</span> {{$project->client->organization}}</h6>
                    <small>{{$project->client->address}}</small>
                    </div>
                </div>
                <!-- Client Name Area Ends-->
                <!-- Project Detail Area Starts-->
                <div class="my-2 bg-white rounded shadow-sm border">
                    <div class='border-bottom border-gray'>
                        <span class="pb-0 pl-2 mb-0">Project Details</span>
                    </div>
                    @foreach($project->contacts as $contact)
                    <div class="media text-muted pt-1">
                        <p class="media-body pl-2 mb-0 small lh-125 border-bottom border-gray">
                            <span class="text-primary">Contact Person: {{$contact->name}}</span>  
                            <span class="text-success">&#9742; {{$contact->contact}} </span>
                        </p>
                    </div>
                    @endforeach
                    <!-- Client Area Ends-->
                    <!-- Project Management Area Starts-->
                    <div class="media text-muted">
                        <p class="media-body pl-2 mb-0 small lh-125">
                            <span class="text-danger">Deadline: {{$project->deadline}}</span>  
                        </p>
                    </div>
                    <!-- Project Management Area Ends-->
                </div>
                <!-- Project Detail Area Ends-->
                <!-- Project Status Area Starts-->
                <div class="my-1 p-2 bg-white rounded shadow-sm">
                    <h6 class="border-bottom border-gray pb-2 mb-0">Allocation Status</h6>
                    <span class="small">Total Allocation: {{$project->allocation}}%</span>
                    <div class="progress mb-1 bg-dark" style="height: 4px;">
                        <div
                            @if ($project->allocation>80)
                                class="progress-bar bg-success"
                            @else
                                class="progress-bar bg-danger"
                            @endif 
                            role="progressbar" 
                            style="width: {{$project->allocation}}%;" 
                            aria-valuenow="25" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
                <!-- Project Status Area Ends-->
                <div class="my-1 p-2 bg-white rounded shadow-sm">
                    <div class='border-bottom border-gray'>
                    <h6 class="pb-2 mb-0">Completion Status</h6>
                    </div>
                    
                    <span class="small">Total Completion: {{$project->completion}}%</span>
                    <div class="progress mb-1 bg-dark" style="height: 4px;">
                        <div
                            @if ($project->completion>80)
                                class="progress-bar bg-success"
                            @else
                                class="progress-bar bg-danger"
                            @endif 
                            role="progressbar" 
                            style="width: {{$project->completion}}%;" 
                            aria-valuenow="25" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        <!-- Project Details Column Ends-->
        <!-- Enquiry Column Starts-->
            <div class='col-md-4'>
                <div class="p-2 my-1 text-white-50 bg-primary rounded shadow-sm">
                    <div class="lh-100">
                        <h6 class="mb-0 text-white lh-100">Enquiries</h6>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span> Number of Enquiries: {{count($project['enquiries'])}} </span>
                            <a href="/enquiries/create/{{$project->id}}" class='text-white small'>Add</a>
                        </div>
                    </div>
                </div>
                <div id='enqdiv' style='max-height:60vh;overflow-x:hidden;overflow-y:scroll' class="rounded shadow-sm">
                    @include('enquiries.index', ['enquiries'=>$project['enquiries']])
                </div>
            </div>
        <!-- Enquiry Column Ends-->
        <!-- Task Column Starts-->
            <div class='col-md-4'>
                <div class="p-2 my-1 text-white-50 bg-primary rounded shadow-sm">
                    <h6 class="mb-0 text-white lh-100">Tasks</h6>
                    <div class="d-flex justify-content-between align-items-center small">
                        <span> 
                            @if(count($project->tasks) == 0)
                                No Task defined yet
                            @else
                                Total number of tasks: {{count($project->tasks)}}
                            @endif 
                        </span>
                        @isset($project)
                            <a href="javascript:void(0)" class="text-white small" onclick="ajaxFunction('showAddTask', '{{ $project->id }}' , 'taskdiv')">
                        @else 
                            <a href="javascript:void(0)" class="text-white small" onclick="ajaxFunction('showAddTask', '{{ $project_id }}' , 'taskdiv')">
                        @endisset
                                Add Task
                            </a>
                    </div>
                </div>
                <div id='taskdiv'>
                @if(count($project->tasks) > 0)
                @include('tasks.index', ['tasks'=>$project->tasks, 'allocation'=>$project->allocation])
                @endif
                </div>
            </div>
        <!-- Task Column Ends-->
        </div>

        </div>
    </main>    
@endsection
<script src="{{ asset('js/projects.js?version=0.2') }}" defer></script>
<script src="{{ asset('js/ajaxformprocess.js') }}" defer></script>
<script src="{{ asset('js/deleteform.js') }}" defer></script>