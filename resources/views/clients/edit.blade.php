@extends('layouts.app')

@section('content')
    <h1>Edit Client</h1>
    {!! Form::open(['action' => ['ClientsController@update', $assignment->id], 'method'=>'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name',$assignment->name,['class'=>'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('organization','Organization')}}
            {{Form::textarea('organization',$assignment->organization,['class'=>'form-control', 'placeholder' => 'Organization'])}}
        </div>
        <div class="form-group">
            {{Form::label('address','Address')}}
            {{Form::text('address',$assignment->address,['class'=>'form-control', 'placeholder' => 'Address'])}}
        </div>
        {{Form::hidden('_method','PUT')}}
        {{Form::submit('Submit',['class'=>'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection