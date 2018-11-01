@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-6">
            <div class="card-deck">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class='row'>
                            <div class="col-md-6">
                                <h6 class="my-0 font-weight-normal">Client Listings</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" id="button-addon2">Button</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-right">
                                <small>
                                    <a 
                                        href="javascript:void(0)"
                                        onclick="ajaxFunction('showCreateClient', '' , 'client-container')">
                                        Create new Client
                                    </a>
                                </small> 
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    @foreach($assignments as $assignment)
                        <div class="align-items-center p-1 my-1 text-black-50 bg-light rounded border-bottom">
                            <div class='row'>
                                <div class="col-md-6 col-lg-9 col-sm-12">
                                    <h6 class="mb-0 text-primary lh-100">{{$assignment->organization}}</h6>
                                </div>
                                <div class="col-md-6 col-lg-3 col-sm-12">
                                <small>
                                    <a href="javascript:void(0)" class='text-danger' onclick="deleteClient('{{$assignment->name}}','{{$assignment->id}}')">Delete</a>
                                    <a href="javascript:void(0)" class='text-success' onclick="ajaxFunction('viewclient', '{{$assignment->id}}' , 'client-container')">Details</a>
                                    <a href="javascript:void(0)" class='text-success' onclick="ajaxFunction('viewclient', '{{$assignment->id}}' , 'client-container')">Projects</a>
                                </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6" id='client-container'>
        @include('clients.create')
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/clients.js?version=0.3') }}"></script>