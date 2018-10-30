@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row" >
            <div class="col-md-6">
                <div class="card flex-md-row mb-4 shadow-sm h-md-250">
                    <div class="card-body d-flex flex-column align-items-start">
                        <strong class="d-inline-block mb-2 text-primary">List of configured users</strong>
                        <table class="table table-hover table-sm table-responsive-sm">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">User Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php 
                                $i = 0;
                                $flag = 1;
                            @endphp
                            @foreach($users as $user)
                                @if($user->id === Auth::user()->id)
                                    @php 
                                        $me = $user;
                                        $flag = 0;
                                    @endphp
                                @else 
                                    @php 
                                        $flag = 1;
                                    @endphp
                                @endif
                                @php
                                    $i++
                                @endphp
                                <tr>
                                <th scope="row"> {{$i}} </th>
                                <td> {{$user->name}} </td>
                                <td> {{$user->email}} </td>
                                <td> {{$user->role_name}}</td>
                                <td> 
                                    @if($flag)
                                    <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteUser('{{$user->name}}','{{$user->id}}')">Delete</a>
                                    <a href="javascript:void(0)" class="btn btn-success" onclick="ajaxFunction('viewuser', '{{$user->id}}', 'user-container' )">Details</a>
                                    <form 
                                        id="user-delete-form-{{$user->id}}"
                                        method="post"
                                        action="{{route('users.destroy', [$user->id])}}" 
                                        >
                                        <input type="hidden" name="_method" value="delete">
                                        {{csrf_field()}}
                                    </form>
                                    @endif
                                </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>              
                <div class='m-2'>
                    <a href="/users/create" class="btn btn-primary">Create new User</a>
                </div>
            </div>      
        </div>
        
        <div class="col-md-6">
          <div class="card flex-md-row mb-4 shadow-sm h-md-250" id='user-container'>
            <div class="card-body d-flex flex-column align-items-start">
              <strong class="d-inline-block mb-2 text-success">My Profile</strong>
              <h3 class="mb-0">
                <a class="text-dark" href="#">Details of "{{ Auth::user()->name }}"</a>
              </h3>
              
              @include('users.show', ['user'=>$me])
            </div>
            
          </div>
        </div>
      </div>
    </div>
@endsection
<script src="{{ asset('js/users.js?version=0.1') }}"></script>