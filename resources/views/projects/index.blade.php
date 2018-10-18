@extends('layouts.app')

@section('content')
    <h1>Projects</h1>
        <div class="well">
            <div class="row">
                    {{-- <div class="col-md-8 col-sm-8">
                        <h2>Client list</h2>
                        <h3><a href="/projects/{{$project->id}}">{{$project->project_name}}</a></h3>
                        <small><p>of description: {{$project->description}}</p>
                        <p>created at {{$project->created_at}}</p></small>
                    </div> --}}
                @if(count($projects) > 0)                   
                    <div class="col-md-8 col-sm-8">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <tr><th>ID</th><th>NAME</th><th>DESCRIPTION</th><th></th></tr>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>{{$project->id}}</td>
                                            <td><a href="/projects/{{$project->id}}">{{$project->project_name}}</a></td>
                                            <td>{{$project->description}}</td>
                                            <td><a href="/projects/{{$project->id}}/edit" class="btn btn-primary">Edit</a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>                        
                    </div>
                @else
                    <p>No Projects found</p>
                @endif                    
            </div>
        </div>
    <div class="card-body">
        <a href="/projects/create" class="btn btn-primary">Create Project</a>
    </div>
@endsection