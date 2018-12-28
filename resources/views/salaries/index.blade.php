@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-12">
            <div class="card mb-4 shadow-sm h-md-250">
                <div class=" mb-0 bg-white rounded">
                    <div class="media text-muted">
                        <div class="media-body small">
                            <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                                <strong class="text-dark pl-1 pt-1">List of configured Salary</strong>
                                <a href="/salaries/create" class='small'>Create Salary</a>
                            </div>
                            <strong class="d-inline-block mb-2 text-primary">List of Salary</strong> 
                            <div class="row m-0 bg-light border-bottom w-100">
                                <div class="col-md-9 text-primary pl-1 text-success"> 
                                    <table class='small w-100' > 
                                    
                                    @foreach ($heads as $head)
                                    <th>{{$head}}</th>
                                    @endforeach
                                    {{-- ////////////Table Headers////////////// --}}
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
</div>
@endsection
{{-- <script src="{{ asset('js/salarystructure.js?version=0.1') }}"></script> --}}