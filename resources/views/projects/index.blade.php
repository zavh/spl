@extends('layouts.app')

@section('content')
    <div class="container-fluid">
      <div class="card-deck text-center">
        <!-- Unllocated Project Section Starts -->
        <div class="card mb-4 shadow-sm">
          <div class="card-header text-white bg-danger m-0 p-0">
            <span class="my-0 font-weight-normal">Unassigned Projects</span>
            <span style='position:absolute;right:10px'>
            <a href="/projects/create" style='color:white'>Create</a>
            </span>
          </div>
          <!-- Body -->
          <div class="card-body p-0 m-0 small">

            @foreach($punalloc as $project)
            <div class="border-bottom">
                <div class='text-left bg-light border-bottom'>
                    <span class="mx-2">Cleint: {{$project->client->organization}}</span>
                </div>
                <p class='text-left mx-4 mb-0 pb-0'>
                    Project title: <a href="/projects/{{$project->id}}">{{$project->project_name}}</a>
                </p>
                <div class='border-top text-left mx-4'>
                        Allocation: {{$project->allocation}}%
                </div>
            </div>
            @endforeach

          </div>
          <!-- Body -->
        </div>
        <!-- Unllocated Project Section Ends -->
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