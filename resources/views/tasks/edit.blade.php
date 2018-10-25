@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-lg">
                    <div class="card-header text-white bg-primary">
                                    {{ __('Edit Task') }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('tasks.update', [$assignment->id]) }}">
                            @csrf           
                            <div class="form-group row">
                                <label for="task_name" class="col-sm-4 col-form-label text-md-right">{{ __('task_name') }}</label>
                                <div class="col-md-6">
                                    <input id="task_name" type="text" class="form-control{{ $errors->has('task_name') ? ' is-invalid' : '' }}" name="task_name" value="{{ $assignment->task_name }}" required autofocus>
    
                                    @if ($errors->has('task_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('task_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="task_description" class="col-sm-4 col-form-label text-md-right">{{ __('task_description') }}</label>
                                <div class="col-md-6">
                                    <input id="task_description" type="text" class="form-control{{ $errors->has('task_description') ? ' is-invalid' : '' }}" name="task_description" value="{{ $assignment->task_description }}" required>
    
                                    @if ($errors->has('task_description'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('task_description') }}</strong>
                                        </span>
                                    @endif                                
                                </div>
                            </div>
                            <div class="form-group row">
                                    <label for="user_id" class="col-sm-4 col-form-label text-md-right">{{ __('user_id') }}</label>
                                <div class="col-md-6">
                                    <select name="user_id" id="user_id" class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" value="{{ $assignment->user_id }}" required>
                                    <option disabled selected>Select One
                                    @foreach($users as $user)
                                        @if ($user->id == $assignment->id)
                                            <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                        @else  
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endif
                                        
                                    @endforeach
                                    </select>
    
                                    @if ($errors->has('user_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('user_id') }}</strong>
                                        </span>
                                    @endif                                
    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="task_date_assigned" class="col-md-4 col-form-label text-md-right">{{ __('task_date_assigned') }}</label>
                                <div class="col-md-6">
                                    <input id="task_date_assigned" type="date" class="form-control{{ $errors->has('task_date_assigned') ? ' is-invalid' : '' }}" name="task_date_assigned" value="{{ $assignment->task_date_assigned }}" required>
    
                                    @if ($errors->has('task_date_assigned'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('task_date_assigned') }}</strong>
                                        </span>
                                    @endif                                
    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="task_deadline" class="col-md-4 col-form-label text-md-right">{{ __('task_deadline') }}</label>
                                <div class="col-md-6">
                                    <input id="task_deadline" type="date" class="form-control{{ $errors->has('task_deadline') ? ' is-invalid' : '' }}" name="task_deadline" value="{{ $assignment->task_deadline }}" required>
    
                                    @if ($errors->has('task_deadline'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('task_deadline') }}</strong>
                                        </span>
                                    @endif                                
    
                                </div>
                            </div>
                            <input name="_method" type="hidden" value="PUT">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit Task') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection