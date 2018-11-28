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
                    <div class="lh-100">
                        <h6 class="mb-0 text-white lh-100">Tasks</h6>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span> 
                                @if(count($project->tasks) == 0)
                                    <small>No Task defined yet</small>
                                @else
                                    <small>Total number of tasks: {{count($project->tasks)}} </small>
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

                <div class="my-3 p-3 bg-white rounded shadow-sm" id='taskdiv'>
                    @include('tasks.index', ['tasks'=>$project->tasks, 'allocation'=>$project->allocation])
                </div>
            </div>
        <!-- Task Column Ends-->
        </div>
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Progress Report</h6>
            <div class="media text-muted pt-3">
            <img data-src="holder.js/32x32?theme=thumb&amp;bg=007bff&amp;fg=007bff&amp;size=1" alt="32x32" class="mr-2 rounded" style="width: 32px; height: 32px;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2232%22%20height%3D%2232%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_166b868648c%20text%20%7B%20fill%3A%23007bff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A2pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_166b868648c%22%3E%3Crect%20width%3D%2232%22%20height%3D%2232%22%20fill%3D%22%23007bff%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2212.166666746139526%22%20y%3D%2216.9%22%3E32x32%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
            <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                <div class="d-flex justify-content-between align-items-center w-100">
                <strong class="text-gray-dark">Task Name</strong>
                <a href="#">Follow</a>
                </div>
                <span class="d-block">@username</span>
            </div>
            </div>
        </div>

        </div>
    </main>    
@endsection
<script src="{{ asset('js/projects.js?version=0.2') }}" defer></script>
<script src="{{ asset('js/ajaxformprocess.js') }}" defer></script>