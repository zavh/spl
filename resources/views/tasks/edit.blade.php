@extends('layouts.app')

@section('content')
    <h1>Edit Assignment</h1>
    {!! Form::open(['action' => ['TasksController@update', $assignment->id], 'method'=>'POST']) !!}
        <div class="form-group">
            {{Form::label('task_name','task_name')}}
            {{Form::text('task_name',$assignment->task_name,['class'=>'form-control', 'placeholder' => 'task_name'])}}
        </div>
        <div class="form-group">
            {{Form::label('task_description','task_description')}}
            {{Form::textarea('task_description',$assignment->task_description,['class'=>'form-control', 'placeholder' => 'task_description'])}}
        </div>
        <div class="form-group">
            {{Form::label('user_id','user_id')}}
            {{Form::text('user_id',$assignment->user_id,['class'=>'form-control', 'placeholder' => 'user_id'])}}
        </div>
        <div class="form-group">
            {{Form::label('task_date_assigned','task_date_assigned')}}
            {{Form::date('task_date_assigned',$assignment->task_date_assigned,['class'=>'form-control', 'placeholder' => 'task_date_assigned'])}}
        </div>
        <div class="form-group">
            {{Form::label('task_deadline','task_deadline')}}
            {{Form::date('task_deadline',$assignment->task_deadline,['class'=>'form-control', 'placeholder' => 'task_deadline'])}}
        </div>
        {{Form::hidden('_method','PUT')}}
        {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection