@extends('layouts.app')

@section('content')
    <h1>Edit Project</h1>
    {!! Form::open(['action' => ['ProjectsController@update', $assignment->id], 'method'=>'POST']) !!}
        <div class="form-group">
            {{Form::label('project_name','Project Name')}}
            {{Form::text('project_name',$assignment->project_name,['class'=>'form-control', 'placeholder' => 'Project Name','required'=>''])}}
        </div>
        <div class="form-group">
            {{Form::label('client_id','client id')}}
            {{Form::text('client_id',$assignment->client_id,['class'=>'form-control', 'placeholder' => 'client id','required'=>''])}}
        </div>
        <div class="form-group">
            {{Form::label('user_id','user id')}}
            {{Form::text('user_id',$assignment->user_id,['class'=>'form-control', 'placeholder' => 'user_id','required'=>''])}}
        </div>
        <div class="form-group">
            {{Form::label('manager_id','manager id')}}
            {{Form::text('manager_id',$assignment->manager_id,['class'=>'form-control', 'placeholder' => 'manager_id','required'=>''])}}
        </div>
        <div class="form-group">
            {{Form::label('assigned','assigned')}}
            {{Form::date('assigned',$assignment->assigned,['class'=>'form-control', 'placeholder' => 'assigned','required'=>''])}}
        </div>
        <div class="form-group">
            {{Form::label('deadline','deadline')}}
            {{Form::date('deadline',$assignment->deadline,['class'=>'form-control', 'placeholder' => 'deadline','required'=>''])}}
        </div>
        <div class="form-group">
            {{Form::label('description','description')}}
            {{Form::textarea('description',$assignment->description,['class'=>'form-control', 'placeholder' => 'description','required'=>''])}}
        </div>
        <div class="form-group">
            {{Form::label('state','state')}}
            {{ Form::radio('state', 1, false,['required'=>'']) }} Open
            {{ Form::radio('state', 0 , false,['required'=>'']) }} Closed
        </div>
        <div class="form-group">
            {{Form::label('status','status')}}
            {{ Form::radio('status', 1, true,['required'=>'']) }} Won
            {{ Form::radio('status', 0, false,['required'=>'']) }}  Lost        
        </div>
    {{Form::hidden('_method','PUT')}}
    {{ Form::submit('Submit',['class'=>'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection