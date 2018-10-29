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
                    <td> <input type='text' name='name' value="{{$user->name}}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}"> 
                
                {{-- /<tr> --}}
                    @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                {{-- </tr>                    --}}
                </td></tr>
                <tr>
                    <th scope="col">Email</th>
                    <td> <input type='text' name='email' value="{{$user->email}}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}"> 
                {{-- <tr> --}}
                    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                {{-- </tr>                    --}}
                </td></tr>
                <tr>
                    <th scope="col">First Name</th>
                    <td> <input type='text' name='fname' value="{{$user->fname}}" class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" name="fname" value="{{ $user->fname }}"> 
                {{-- <tr> --}}
                    @if ($errors->has('fname'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('fname') }}</strong>
                    </span>
                    @endif
                {{-- </tr>                    --}}
                </td></tr>
                <tr>
                    <th scope="col">Surname Name</th>
                    <td> <input type='text' name='sname' value="{{$user->sname}}" class="form-control{{ $errors->has('sname') ? ' is-invalid' : '' }}" name="sname" value="{{ $user->sname }}"> 
                {{-- <tr> --}}
                    @if ($errors->has('sname'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('sname') }}</strong>
                    </span>
                    @endif
                {{-- </tr>                    --}}
                </td></tr>                
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
                    <td> <input type='number' name='phone' value="{{$user->phone}}" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ $user->phone }}"> 
                {{-- <tr> --}}
                    @if ($errors->has('phone'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                    @endif
                {{-- </tr>                    --}}
                </td></tr>
                <tr>
                    <th scope="col">Address</th>
                    <td> <input type='text' name='address' value="{{$user->address}}" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $user->address }}"> 
                {{-- <tr> --}}
                    @if ($errors->has('address'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                    @endif
                {{-- </tr>                   --}}
                </td></tr>
                <tr>
                    <th scope="col">Role</th>
                    <td>
                        <select name="role_id" class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}" name="role_id" value="{{ $user->role_id }}">
                            @foreach($roles as $role)
                                @if($role->id == $user->role_id)
                                    <option value="{{$role->id}}" selected>{{$role->role_name}}</option>
                                @else 
                                    <option value="{{$role->id}}">{{$role->role_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    
                {{-- <tr> --}}
                    @if ($errors->has('role_id'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('role_id') }}</strong>
                    </span>
                    @endif
                {{-- </tr>                 --}}
            </td></tr>
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