@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Clients</h1>
        @if(count($clients) > 0)
            @foreach($clients as $client)
                <div class="row">      
                    <div class="col-md-6 col-sm-6">        
                        <div class="card text-center">                            
                            {{-- <h2>Client list</h2> --}}
                            <h3><a href="/clients/{{$client->id}}">{{$client->name}}</a></h3>
                            <small><p>of organization: {{$client->organization}}</p>
                            <p>with address {{$client->address}}</p>
                            <p>created at {{$client->created_at}}</p></small>
                        </div>
                    </div>
                </div> 
            @endforeach
            {{-- {{$clients->links()}} --}}
        @else
            <p>No Clients found</p>
        @endif
        <div class="row">
            <div class="text-right col-md-6 col-sm-6">
                <a href="/clients/create" class="btn btn-primary">Create Post</a>
            </div> 
        </div>
        
        
    </div>
@endsection