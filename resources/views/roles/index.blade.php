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
            @foreach($roles as $role)
                <?php $i++?>
                <tr>
                <th scope="row"> {{$i}} </th>
                <td> {{$role->role_name}} </td>
                <td> {{$role->role_description}} </td>
                <td> 
                @if (!($role->role_name === 'admin'))

                    <a href="{{$role->id}}" class="btn btn-danger">Delete</a>
                    <a href="/roles/{{$role->id}}" class="btn btn-success">Details</a>
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
      </div>
    </div>
    @endsection