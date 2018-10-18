@extends('layouts.app')

@section('content')
    <div class="box">
        <p><h1>This is Client: <strong>{{$assignment->name}}</strong></h1></p>
        <p><h3>in Organization:</h3>{{$assignment->organization}}</p>
        <p><h3>created on:</h3>{{$assignment->created_at}}</p>
        {{-- <h1>Clients</h1> --}}
    </div>
    <a href="/clients/{{$assignment->id}}/edit" class="btn btn-primary">Edit</a>
    <a href="/clients" class="btn btn-primary">Go Back</a>

    {{-- {!!Form::open(['action'=>['ClientsController@destroy',$assignment->id],'method'=>'POST','class'=>'pull-right'])!!}
        {{Form::hidden('_method','DELETE')}}
        {{Form::submit('Delete',['class'=>'btn btn-danger'])}}
    {!!Form::close()!!} --}}
    
@endsection