@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class='row'>
        <!-- Open Project Section Starts -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-0 border-bottom bg-primary">
                    <span class="mx-2 font-weight-normal text-white">Project Status : Open</span>
                    <span class='mx-2 small'>
                        <a href="/projects/create" class="text-white">Create</a>
                    </span>
                </div>
                <!-- Body -->
                <div class="card-body p-0 m-0 small">
                @if(count($open)>0)
                @include('projects.projectlist',['projects'=>$open,'status'=>'open'])
                @else
                <span class='mx-4'>No Project in "Open" state</span>
                @endif
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
                @include('projects.projectlist',['projects'=>$expired,'status'=>'expired'])
                @endif
                </div>
                <!-- Body -->
            </div>
        </div>
        <!-- Expired Project Section Ends -->
        <!-- Expired Project Section Starts -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-0 border-bottom bg-success">
                    <span class="mx-2 font-weight-normal text-white">Project Status : Closed</span>
                    <span class='mx-2 small'>
                        <a href="javascript:void(0)" class="text-white" onclick='showSearchForm()'>Search</a>
                    </span>
                </div>
                <!-- Body -->
                <div class="card-body p-0 m-0 small" id='searchresult'>
                @if(count($closed) == 0)
                <span class='mx-4'>No closed Project found</span>
                @else
                <span class='small mx-2'>(Max last 5 project shown)</span>
                @include('projects.projectlist',['projects'=>$closed,'status'=>'closed'])
                @endif
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