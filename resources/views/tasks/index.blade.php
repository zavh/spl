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
                        <h1>Tasks</h1>
                        @if(count($tasks) > 0)
                            @foreach($tasks as $task)
                                <div class="well">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-8">
                                            {{-- <h2>Client list</h2> --}}
                                            <h3><a href="/tasks/{{$task->id}}">{{$task->task_name}}</a></h3>
                                            <small><p>of description: {{$task->task_description}}</p>
                                            <p>user: {{$task->user_id}}</p>    
                                            <p>with assigned date: {{$task->task_date_assigned}}</p>
                                            <p>and deadline: {{$task->task_deadline}}</p></small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- {{$clients->links()}} --}}
                        @else
                            <p>No tasks found</p>
                        @endif
                        <a href="/tasks/create" class="btn btn-primary">Create Task</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection