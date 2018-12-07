<link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,600,700' rel='stylesheet' type='text/css'>
<link href="{{ asset('css/timeline.css') }}" rel="stylesheet">
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
                <!-- Project Contact Area Starts-->
                <div class="my-2 bg-white rounded shadow-sm border">
                    <div class='border-bottom border-gray'>
                        <span class="pb-0 pl-2 mb-0">Client Contacts</span>
                    </div>
                    <div class="accordion" id="contactAccordion">
                    @foreach($project->contacts as $index=>$contact)
                    <div class="small">
                        <div id="contactHeading{{$index}}" class='border-bottom w-100'>                            
                            <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapseContact{{$index}}" aria-expanded="true" aria-controls="collapseContact{{$index}}">
                                    {{$contact->name}}
                            </button>
                        </div>
                        <div id="collapseContact{{$index}}" class="collapse " aria-labelledby="contactHeading{{$index}}" data-parent="#contactAccordion">
                            <div class='border-bottom text-success'> <p class='px-4 mb-0 pb-0'>{{$contact->designation}}</p></div>
                            <div class="text-danger border-bottom"> <span class='px-4'>&#9742; {{$contact->contact}}</span></div>
                        </div>
                    </div>
                    @endforeach
                    </div>
                </div>
                <!-- Project Contact Area Ends-->
                <!-- Project Allocation Area Starts-->
                <div class="my-1 bg-white rounded shadow-sm border">
                    <div class='pl-2 border-bottom'>Allocation Status</div>
                    <div class='px-2 mb-1'>
                        <span class="small">Total Allocation: <span id='al-at-title'>{{$project->allocation}}</span>%</span>
                        <div class="progress mb-1 bg-dark" style="height: 4px;">
                            <div
                                @if ($project->allocation>80)
                                    class="progress-bar bg-success"
                                @else
                                    class="progress-bar bg-danger"
                                @endif 
                                role="progressbar" 
                                style="width:{{$project->allocation}}%;" 
                                aria-valuenow="25" 
                                aria-valuemin="0" 
                                aria-valuemax="100"
                                id='al-at-value'>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Project Allocation Area Ends-->
                <div class="my-1 bg-white rounded shadow-sm border">
                    <div class='pl-2 border-bottom'>Project Status</div>
                    <div class='px-2'>
                        <span class="small">Total Completion: {{($project->completion == NULL) ? 0 :$project->completion}}%</span>
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
                    <!-- Project Dates Area Starts-->
                        <div class="small text-primary mx-2 border-bottom">
                            <span>Project Start Date</span>
                            <strong> &#8674; {{$project->start_date}}</strong>
                        </div>
                        <div class="small text-danger mx-2">
                            <span>Deadline</span>
                            <strong> &#8674; {{$project->deadline}}</strong>
                        </div>
                    <!-- Project Dates Area Ends-->
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
                <div id='enqdiv' style='max-height:80vh;overflow-x:hidden;overflow-y:auto' class="rounded shadow-sm">
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
                                Total number of tasks: <span id='taskcount'>{{count($project->tasks)}}</span>
                            @endif 
                        </span>                        
                            <a href="javascript:void(0)" class="text-white small" onclick="ajaxFunction('showAddTask', '{{ $project->id }}' , 'taskdiv')">
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
        <div class='timelinediv my-4 small' id='projecttimeline'>
            @include('projects.projecttimeline',['project'=>$project])
        </div>
    </main>    
@endsection
<script src="{{ asset('js/projects.js?version=0.2') }}" defer></script>
<script src="{{ asset('js/ajaxformprocess.js') }}" defer></script>
<script src="{{ asset('js/deleteform.js') }}" defer></script>