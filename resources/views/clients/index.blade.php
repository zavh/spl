@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-6">
            <div class="card flex-md-row mb-4 shadow-sm h-md-250">
                <div class="card-body d-flex flex-column align-items-start">
                    <strong class="d-inline-block mb-2 text-primary">List of Clients</strong>
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Client Name</th>
                                <th scope="col">Client Organization</th>
                                <th scope="col">Client Address</th>
                                <th scope="col">Client Contact</th>                                
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0;?>
                                @foreach($assignments as $assignment)
                                    <?php $i++?>
                                        <tr>
                                            <th scope="row"> {{$i}} </th>
                                            <td> {{$assignment->name}} </td>
                                            <td> {{$assignment->organization}} </td>
                                            <td> {{$assignment->address}} </td>
                                            <td> {{$assignment->contact}} </td>
                                            <td> 
                                            @if (!($assignment->role_name === 'admin'))
                                            <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteClient('{{$assignment->name}}','{{$assignment->id}}')">Delete</a>
                                            <a href="javascript:void(0)" class="btn btn-success btn-sm" onclick="ajaxFunction('viewclient', '{{$assignment->id}}' , 'client-container')">Details</a>
                                            <form 
                                                id="client-delete-form-{{$assignment->id}}"
                                                method="post"
                                                action="{{route('clients.destroy', [$assignment->id])}}" 
                                                >
                                                <input type="hidden" name="_method" value="delete">
                                                {{csrf_field()}}
                                            </form>
                                            @else 
                                                <strong>Not configurable</strong>
                                            @endif
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>                                
                </div>
                <div class='m-2'>               
                    <a href="/clients/create" class="btn btn-primary">Create new Client</a>                
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card flex-md-row mb-4 shadow-sm h-md-250" id='clients-container'>
 
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/clients.js?version=0.1') }}"></script>