@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-light text-dark m-0 p-1 pl-2">
                    {{ __('Create new Salary Structure') }}
                </div>
                <div class="card-body pb-0 mb-0">
                    <form method="POST" action="{{ route('salaries.store') }}">
                        @csrf
                        <input type="hidden" name='page' value='{{$page}}'>

                        <!-- Username Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:160px'>{{ __('User') }}</span>
                                </div>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value="" disabled>Select One</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('user_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Username Input Ends -->  

                        <!-- Basic Salary Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:160px'>{{ __('Basic Salary') }}</span>
                                </div>
                                <input id="basic" type="text" class="form-control{{ $errors->has('basic') ? ' is-invalid' : '' }}" name="basic" value="{{ old('basic') }}" required>

                                @if ($errors->has('basic'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('basic') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Basic Salary Input Ends -->             

                        <div class='form-group row mt-2'>
                            <div class="input-group input-group-sm col-md-6">
                                <input type="submit" class="btn btn-outline-primary btn-sm btn-block" value="Create New">
                            </div>
                            <div class="input-group input-group-sm col-md-6">
                            <a href="{{ $page == null ? '/salaries' : '/salaries/create'}}" class="btn btn-outline-secondary btn-sm btn-block">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection