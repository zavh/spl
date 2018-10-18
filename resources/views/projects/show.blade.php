@extends('layouts.app')

@section('content')
    <div class="box">
        <ul>
            <li>
                <h3>This is Project: </h3> <p>{{$assignment->project_name}}</p>
                <h3> assigned by: </h3><p>{{$assignment->client_id}}</p>
                <h3>to: </h3><p>{{$assignment->user_id}}</p>
                <h3>managed by: </h3> <p>{{$assignment->manager_id}}</p>
                <h3>given on: </h3><p>{{$assignment->assigned}}</p>
                <h3>to be completed by: </h3><p>{{$assignment->deadline}}</p>
                <h3>with description: </h3> <p>{{$assignment->description}}</p>
                <h3> state:</h3><p>{{$assignment->state}}</p>
                <h3> and status:</h3><p>{{$assignment->status}}</p>
            </li>
        </ul>
    </div>
    <a href="/projects/{{$assignment->id}}/edit" class="btn btn-primary">Edit</a>
    <a href="/projects" class="btn btn-primary">Go Back</a>

    {!!Form::open(['action'=>['ProjectsController@destroy',$assignment->id],'method'=>'POST','class'=>'pull-right'])!!}
        {{Form::hidden('_method','DELETE')}}
        {{Form::submit('Delete',['class'=>'btn btn-danger'])}}
    {!!Form::close()!!}
    
@endsection