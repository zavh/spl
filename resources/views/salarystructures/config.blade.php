<script src="{{ asset('js/configsalarystructure.js') }}"></script>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-light text-dark m-0 p-1 pl-2">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <span>{{ __('Configure Salary Structure') }}</span>
                        @if($response['status'] == 'failed')
                            <span class="text-danger small"> {{$response['message']}} </span>
                        @endif
                    </div>
                </div>
                <div class="card-body pb-0 mb-0">
                    <div class="form-group row">
                        <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                            <div class="input-group-prepend">
                                <span class="input-group-text" style='width:160px'>{{ __('Parameter Name') }}</span>
                            </div>
                            <input id="param_name" type="text" class="form-control" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="addParam(this)" data-index="{{$response['numfields']}}">Add
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="params_container" class="m-2 mt-4">
                    @if($response['status'] == 'success')
                        @foreach($response['data'] as $index => $data)
                            <div class="form-group row" id="param_{{$index}}">
                                @include('salarystructures.configsnippet',['data'=>$data,'index'=>$index])
                            </div>
                            <script>
                            root[{{$index}}] = new Category("{{$data['param_name']}}", "{{$data['param_uf_name']}}", 0);
                            </script>
                        @endforeach
                    @endif
                        <div class="form-group row" id="param_{{$response['numfields']}}"></div>
                    </div>
                    
                    <div class='form-group row mt-2'>
                        <div class="input-group input-group-sm col-md-6">
                            <input type="button" class="btn btn-outline-primary btn-sm btn-block" value="Save" onclick="submitConfig()">
                        </div>
                        <div class="input-group input-group-sm col-md-6">
                        <a href='/salarystructures' class="btn btn-outline-secondary btn-sm btn-block">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection