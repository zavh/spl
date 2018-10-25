@extends('layouts.app')

@section('content')
    <div class="box">
        <<ul>
            <li>
                <h3>This is Task: </h3> <p>{{$assignment->task_name}}</p>
                <h3> described as: </h3><p>{{$assignment->task_description}}</p>
                <h3> given to: </h3><p>{{$assignment->user_id}}</p>
                <h3> given on: </h3> <p>{{$assignment->task_date_assigned}}</p>
                <h3>to be completed by: </h3><p>{{$assignment->task_deadline}}</p>
            </li>
        </ul>
    </div>
    <a href="/tasks/{{$assignment->id}}/edit" class="btn btn-primary">Edit</a>
    
    <a href="/tasks" class="btn btn-primary">Go Back</a>

    {{-- {!!Form::open(['action'=>['TasksController@destroy',$assignment->id],'method'=>'POST','class'=>'pull-right'])!!}
        {{Form::hidden('_method','DELETE')}}
        {{Form::submit('Delete',['class'=>'btn btn-danger'])}}
    {!!Form::close()!!} --}}

    <form method="POST" action="{{ route('tasks.destroy', [$assignment->id]) }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}

        <div class="form-group">
            <input type="submit" class="btn btn-danger delete-user" value="Delete">
        </div>
    </form>
       
@endsection