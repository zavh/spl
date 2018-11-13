@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb" style='font-size:12px'>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="/users">Users</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create User</li>
  </ol>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <!-- Header Starts -->
                <div class="card-header">
                    {{ __('Create new user') }}
                </div>
                <!-- Header Ends -->
                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <!-- Name Input Starts -->
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Name Input Ends -->
                        <!-- Email Input Starts -->
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Email Input Ends -->
                        <!-- Password Input Starts -->
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Password Input Ends -->
                        <!-- Confirm Password Input Starts -->
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <!-- Confirm Password Input Ends -->
                        <!-- Department Input Starts -->
                        <div class="form-group row">
                            <label for="department" class="col-md-4 col-form-label text-md-right">{{ __('Department') }}</label>
                            <div class="col-md-6">
                                <select name="department" id="department" class="form-control" required>
                                <option value="" disabled>Select One</option>
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Department Input Ends -->
                        <!-- Designation Input Starts -->
                        <div class="form-group row">
                            <label for="designation" class="col-md-4 col-form-label text-md-right">{{ __('Designation') }}</label>
                            <div class="col-md-6">
                                <select name="designation" id="designation" class="form-control" required>
                                <option value="" disabled>Select One</option>
                                @foreach($designations as $designation)
                                    <option value="{{$designation->id}}">{{$designation->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Designation Input Ends -->
                        <!-- Role Input Starts -->
                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('User Role') }}</label>
                            <div class="col-md-6">
                                <select name="role" id="role" class="form-control" required>
                                <option value="" disabled>Select One</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->role_name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Role Input Ends -->
                        <div class='row d-flex justify-content-center'> 
                            <input type="submit" class="btn btn-primary btn-sm m-2" value="Create">
                            <a href="/users" class="btn btn-secondary btn-sm m-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
