@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-primary">
                    {{ __('Create new Client') }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('clients.store') }}" style='font-size:10px'>
                        @csrf
                        <input type="hidden" name='page' value='{{$page}}'>
                        <!-- Organization Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Organization') }}</span>
                                </div>
                                <input id="organization" type="text" class="form-control{{ $errors->has('organization') ? ' is-invalid' : '' }}" name="organization" value="{{ old('organization') }}" required>

                                @if ($errors->has('organization'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('organization') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Organization Name Input Ends -->
                        <!-- Address Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Address') }}</span>
                                </div>
                                <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" required>

                                @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                    
                        <!-- Address Input Ends -->                        
                        <!-- Contact Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Contact Name') }}</span>
                                </div>
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Contact Name Input Ends -->
                        <!-- Contact Designation Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Designation') }}</span>
                                </div>
                                <input id="designation" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="designation" value="{{ old('designation') }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Contact Designation Input Ends -->                        
                        <!-- Client Contact Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Contact Number') }}</span>
                                </div>
                                <input type="text" class="form-control{{ $errors->has('contact') ? ' is-invalid' : '' }}" name="contact" value="{{ old('contact') }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="contact"></strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Client Contact Input Ends --> 
                        <!-- Client Background Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Background') }}</span>
                                </div>
                                <input type="text" class="form-control{{ $errors->has('background') ? ' is-invalid' : '' }}" name="background" value="{{ old('background') }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong id="background"></strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Client Background Input Ends -->                   
                        <div class='row d-flex justify-content-center'> 
                            <input type="submit" class="btn btn-primary btn-sm m-2" value="Create New">
                            <a href="{{ $page == null ? '/clients' : '/projects/create'}}" class="btn btn-secondary btn-sm m-2">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection