@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">
                    Changing Details of Username : <strong>"{{$user->name}}"</strong>
                </div>

                <div class="card-body">
<form method='post' action="{{route('users.update', [$user->id])}}" style='width:100%'>
        {{csrf_field()}}
        <input type="hidden" name="_method" value="put">
        <input type="hidden" name="command" value="profile_update">
        <table class="table table-hover table-sm">
            <tbody>
                <tr>
                    <th scope="col">User Name</th>
                    <td> <input type='text' name='name' value="{{$user->name}}" class="form-control"> </td>
                    
                </tr>
                <tr>
                    <th scope="col">Email</th>
                    <td> <input type='text' name='email' value="{{$user->email}}" class="form-control"> </td>
                    
                </tr>
                <tr>
                    <th scope="col">First Name</th>
                    <td> <input type='text' name='fname' value="{{$user->fname}}" class="form-control"> </td>
                    
                </tr>
                <tr>
                    <th scope="col">Surname Name</th>
                    <td> <input type='text' name='sname' value="{{$user->sname}}" class="form-control"> </td>
                    
                </tr>                
                <tr>
                    <th scope="col">Active From</th>
                    <td> {{$user->created_at}} </td>
                    
                </tr>
                <tr>
                    <th scope="col">Last Activity</th>
                    <td> {{$user->updated_at}} </td>
                </tr>
                <tr>
                    <th scope="col">Phone</th>
                    <td> <input type='text' name='phone' value="{{$user->phone}}" class="form-control"> </td>
                    
                </tr>
                <tr>
                    <th scope="col">Address</th>
                    <td> <input type='text' name='address' value="{{$user->address}}" class="form-control"> </td>
                    
                </tr>
                <tr>
                    <th scope="col">Role</th>
                    <td>
                        <select name="role_id" class="form-control">
                            @foreach($roles as $role)
                                @if($role->id == $user->role_id)
                                    <option value="{{$role->id}}" selected>{{$role->role_name}}</option>
                                @else 
                                    <option value="{{$role->id}}">{{$role->role_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="submit" class="btn btn-primary btn-lg btn-block" value="Submit Changes">
        </form>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection