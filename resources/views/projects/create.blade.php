@extends('layouts.app')

@section('content')
    <h1>Create Project</h1>
    {!! Form::open(['action' => 'ProjectsController@store', 'method'=>'POST']) !!}
        <div class="form-group">
            {{Form::label('project_name','project_name')}}
            {{Form::text('project_name','',['class'=>'form-control', 'placeholder' => 'project_name'])}}
        </div>
        <div class="form-group">
            {{Form::label('client_id','client_id')}}
            {{Form::text('client_id','',['class'=>'form-control', 'placeholder' => 'client_id'])}}
        </div>
        <div class="form-group">
            {{Form::label('user_id','user_id')}}
            {{Form::text('user_id','',['class'=>'form-control', 'placeholder' => 'user_id'])}}
        </div>
        <div class="form-group">
            {{Form::label('manager_id','manager_id')}}
            {{Form::text('manager_id','',['class'=>'form-control', 'placeholder' => 'manager_id'])}}
        </div>
        <div class="form-group">
            {{Form::label('assigned','assigned')}}
            {{Form::date('assigned','',['class'=>'form-control', 'placeholder' => 'assigned'])}}
        </div>
        <div class="form-group">
            {{Form::label('deadline','deadline')}}
            {{Form::date('deadline','',['class'=>'form-control', 'placeholder' => 'deadline'])}}
        </div>
        <div class="form-group">
            {{Form::label('description','description')}}
            {{Form::textarea('description','',['class'=>'form-control', 'placeholder' => 'description'])}}
        </div>
        {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection