{{-- @extends('layouts.app')

@section('content')
    <h1>Create Assignment</h1>
    {!! Form::open(['action' => 'TasksController@store', 'method'=>'POST']) !!}
        <div class="form-group">
            {{Form::label('task_name','task_name')}}
            {{Form::text('task_name','',['class'=>'form-control', 'placeholder' => 'task_name'])}}
        </div>
        <div class="form-group">
            {{Form::label('task_description','task_description')}}
            {{Form::textarea('task_description','',['class'=>'form-control', 'placeholder' => 'task_description'])}}
        </div>
        <div class="form-group">
            {{Form::label('user_id','user_id')}}
            {{Form::text('user_id','',['class'=>'form-control', 'placeholder' => 'user_id'])}}
        </div>
        <div class="form-group">
            {{Form::label('task_date_assigned','task_date_assigned')}}
            {{Form::date('task_date_assigned','',['class'=>'form-control', 'placeholder' => 'task_date_assigned'])}}
        </div>
        <div class="form-group">
            {{Form::label('task_deadline','task_deadline')}}
            {{Form::date('task_deadline','',['class'=>'form-control', 'placeholder' => 'task_deadline'])}}
        </div>
        {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection --}}


<div class="card shadow-lg">
    <div class="card-header text-white bg-primary">
                    {{ __('Create new Task') }}
    </div>
    <div class="card-body">

    </div>
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf           
            <div class="form-group row">
                <label for="task_name" class="col-sm-4 col-form-label text-md-right">{{ __('task_name') }}</label>
                <div class="col-md-6">
                    <input id="task_name" type="text" class="form-control{{ $errors->has('task_name') ? ' is-invalid' : '' }}" name="task_name" value="{{ old('task_name') }}" required autofocus>
                </div>
            </div>
            <div class="form-group row">
                <label for="task_description" class="col-sm-4 col-form-label text-md-right">{{ __('task_description') }}</label>
                <div class="col-md-6">
                    <input id="task_description" type="text" class="form-control{{ $errors->has('task_description') ? ' is-invalid' : '' }}" name="task_description" value="{{ old('task_description') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <select name="user_id" id="user_id" class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" required>
                <option disabled selected>Select One</div>
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group row">
                <label for="task_date_assigned" class="col-md-4 col-form-label text-md-right">{{ __('task_date_assigned') }}</label>
                <div class="col-md-6">
                    <input id="task_date_assigned" type="date" class="form-control{{ $errors->has('task_date_assigned') ? ' is-invalid' : '' }}" name="task_date_assigned" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="task_deadline" class="col-md-4 col-form-label text-md-right">{{ __('task_deadline') }}</label>
                <div class="col-md-6">
                    <input id="task_deadline" type="date" class="form-control{{ $errors->has('task_deadline') ? ' is-invalid' : '' }}" name="task_deadline" required>
                </div>
            </div>
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Create Client') }}
                </button>
            </div>
        </form>
</div>