@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-6">
            <div class="card flex-md-row mb-4 shadow-sm h-md-250">
                <div class="card-body d-flex flex-column align-items-start">
                    <strong class="d-inline-block mb-2 text-primary">List of Designations</strong>
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">designation Name</th>
                                <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0;?>
                                @foreach($designations as $designation)
                                    <?php $i++?>
                                        <tr>
                                            <th scope="row"> {{$i}} </th>
                                            <td> {{$designation->name}} </td>
                                            <td> <a href="/designations/{{$designation->id}}/edit" class="pr-2 pt-1">Edit designation</a></td> 
                                            <td>
                                                <form method="POST" action="/designations/{{$designation->id}}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input type="submit" class="pr-2 pt-1" value="Delete Designation">
                                                </form>
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>                                
                </div>
                <div class='m-2'>               
                    <a href="/designations/create" class="btn btn-primary">Create new designation</a>                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection