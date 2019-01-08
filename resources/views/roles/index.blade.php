@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-6 col-lg-6">
            <div class="card mb-4 shadow-sm h-md-250">
                <div class=" mb-0 bg-white rounded">
                    <div class="media text-muted">
                        <div class="media-body small">
                            <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                                <strong class="text-dark pl-1 pt-1">List of configured Roles</strong>
                                <a href="/roles/create" class="pr-2 pt-1">Create new role</a>
                            </div>
                            @foreach($assignments as $role)
                                <div class="row d-flex justify-content-between m-0 bg-light border-bottom w-100">
                                    <strong class='text-success pl-2'>{{$role->role_name}}</strong>
                                    <div class="d-flex justify-content-between py-1">
                                    <a href="javascript:void(0)" class="badge badge-pill btn btn-outline-danger mx-2" onclick="deleteRole('{{$role->role_name}}','{{$role->id}}')">Delete</a>
                                    <a href="javascript:void(0)" class="badge badge-pill btn btn-outline-success mx-2" onclick="ajaxFunction('viewrole', '{{$role->id}}' , 'role-container')">Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6" id='role-container'>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/roles.js?version=0.1') }}"></script>