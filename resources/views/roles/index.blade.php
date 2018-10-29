@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-6">
            <div class="card flex-md-row mb-4 shadow-sm h-md-250">
                <div class="card-body d-flex flex-column align-items-start">
                    <strong class="d-inline-block mb-2 text-primary">List of configured Roles</strong>
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Role Name</th>
                                <th scope="col">Role Description</th>
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0;?>
                                @foreach($assignments as $assignment)
                                    <?php $i++?>
                                        <tr>
                                            <th scope="row"> {{$i}} </th>
                                            <td> {{$assignment->role_name}} </td>
                                            <td> {{$assignment->role_description}} </td>
                                            <td> 
                                            @if (!($assignment->role_name === 'admin'))
                                                <a href="{{$assignment->id}}" class="btn btn-danger btn-sm">Delete</a>
                                                <a 
                                                    href="javascript:void(0)" 
                                                    class="btn btn-success btn-sm" 
                                                    onclick="ajaxFunction('viewrole', '{{$assignment->id}}' , 'role-container')">
                                                    Details
                                                </a>
                                            @else 
                                                <strong>Not configurable</strong>
                                            @endif
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>                                
                </div>
                <div class='m-2'>               
                    <a href="/roles/create" class="btn btn-primary">Create new role</a>                
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card flex-md-row mb-4 shadow-sm h-md-250" id='role-container'>
 
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/roles.js?version=0.1') }}"></script>