@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-12">
            <div class="card mb-4 shadow-sm h-md-250">
                <div class=" mb-0 bg-white rounded">
                    <div class="media text-muted">
                        <div class="media-body small">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <strong class="text-dark pl-1 pt-1">List of configured Salary</strong>
                                <form action="/salaries/upload" method="post" enctype="multipart/form-data" class="m-2">
                                    @csrf
                                    Select Monthly Data:
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                    <input type="submit" value="Upload Image" name="submit">
                                </form>
                            </div>
                            <div class="row m-0 bg-light border-bottom">
                                <table class='small mb-0 text-center table-striped' style="width:100%;overflow:auto"> 
                                <tr class="table-info">
                                @foreach ($heads as $head)
                                <th>{{$head}}</th>
                                @endforeach
                                </tr>
                                
                                @foreach ($salaries as $index=>$salary)
                                <tr>
                                    @foreach ($salary as $breakdown=>$value)
                                        <td>{{$value}}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- <script src="{{ asset('js/salarystructure.js?version=0.1') }}"></script> --}}