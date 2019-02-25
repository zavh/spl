@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-12 col-lg-8">
            <div class="card mb-4 shadow-sm h-md-250">
                <div class=" mb-0 bg-white rounded">
                    <div class="media text-muted">
                        <div class="media-body small">
                            <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                                <strong class="text-dark pl-1 pt-1">List of configured Departments</strong>
                                <a href="/departments/create" class="pr-2 pt-1">Create new Department</a>
                            </div>
                            @include('departments.dptsnippet',['departments'=>$departments])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/departments.js?version=0.1') }}"></script>