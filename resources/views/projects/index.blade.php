@extends('layouts.app')

@section('content')
    <div class="container">
      <div class="card-deck mb-3 text-center">
        <div class="card mb-4 shadow-sm">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Recent Projects</h4>
          </div>
          <div class="card-body">
            <a href="/projects/create" class="btn btn-lg btn-block btn-outline-primary">Create Project</a>
            <a href="/projects/create" class="btn btn-lg btn-block btn-outline-primary">Show All</a>
          </div>
        </div>
        <div class="card mb-4 shadow-sm">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Open Projects</h4>
          </div>
          <div class="card-body">
            <h5 class="card-title pricing-card-title">Total Open: <small class="text-muted">{{count($projects)}}</small></h5>
            <table class='table'>
            <thead>
                <tr>
                    <th scope="col">Client</th>
                    <th scope="col">Manager</th>
                    <th scope="col">View</th>
                </tr>
            <thead>
            @foreach($projects as $project)
                <tr>
                    <td><a href="/projects/{{$project->id}}">{{$project->project_name}}</a></td>
                    <td>{{$project->description}}</td>
                    <td><a href="/projects/{{$project->id}}/edit" class="btn btn-primary">Edit</a></td>
                </tr>
            @endforeach
            </table>
            <button type="button" class="btn btn-sm btn-block btn-primary">Show All</button>
          </div>
        </div>
        <div class="card mb-4 shadow-sm">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Closed Projects</h4>
          </div>
          <div class="card-body">
            <h5 class="card-title pricing-card-title">Recently Closed</h5>
            <table class='table' style='font-size:12px'>
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Client</th>
                    <th scope="col">Status</th>
                    <th scope="col">View</th>
                </tr>
                <tr>
                        <td></td><td></td><td></td><td></td>
                </tr>
            </thead>
            </table>
            <p>
                Date: <input type='radio' name='search_criteria' value='Date Range'>
                Client: <input type='radio' name='search_criteria' value='Client Name'>
                Project Manager: <input type='radio' name='search_criteria' value='Project Manager'>
            </p>
            <p>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
            </p>            
            <button type="button" class="btn btn-sm btn-block btn-primary">Search</button>
          </div>
        </div>
      </div>
    </div>
@endsection