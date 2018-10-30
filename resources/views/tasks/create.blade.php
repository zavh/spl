@extends('layouts.app')
@section('content')

<main class="py-4">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                            {{ __('Create new Task') }}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf           
                    <div class="form-group row">
                        <label for="task_name" class="col-sm-4 col-form-label text-md-right">{{ __('Task Name') }}</label>
                        <div class="col-md-6">
                            <input id="task_name" type="text" class="form-control{{ $errors->has('task_name') ? ' is-invalid' : '' }}" name="task_name" value="{{ old('task_name') }}" required autofocus>

                            @if ($errors->has('task_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('task_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="task_description" class="col-sm-4 col-form-label text-md-right">{{ __('Task Description') }}</label>
                        <div class="col-md-6">
                            <input id="task_description" type="text" class="form-control{{ $errors->has('task_description') ? ' is-invalid' : '' }}" name="task_description" value="{{ old('task_description') }}" required>

                            @if ($errors->has('task_description'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('task_description') }}</strong>
                                </span>
                            @endif                                
                        </div>
                    </div>
                    <div class="form-group row">
                            <label for="user_id" class="col-sm-4 col-form-label text-md-right">{{ __('Assign User/s') }}</label>
                        <div class="col-md-6">
                            <select name="user_id" id="user_id" class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" required multiple>
                            <option disabled selected>Select One
                            @foreach($users as $user)
                                @if($user->name != 'admin')
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
                        <label for="task_date_assigned" class="col-md-4 col-form-label text-md-right">{{ __('Assigned Date') }}</label>
                        <div class="col-md-6">
                            <input id="task_date_assigned" type="date" class="form-control{{ $errors->has('task_date_assigned') ? ' is-invalid' : '' }}" name="task_date_assigned" required>

                            @if ($errors->has('task_date_assigned'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('task_date_assigned') }}</strong>
                                </span>
                            @endif                                

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="task_deadline" class="col-md-4 col-form-label text-md-right">{{ __('Deadline') }}</label>
                        <div class="col-md-6">
                            <input id="task_deadline" type="date" class="form-control{{ $errors->has('task_deadline') ? ' is-invalid' : '' }}" name="task_deadline" required>

                            @if ($errors->has('task_deadline'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('task_deadline') }}</strong>
                                </span>
                            @endif                                

                        </div>
                    </div>
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Create Task') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>