@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-light text-dark m-0 p-1 pl-2">
                    {{ __('Create new Salary Structure') }}
                </div>
                <div class="card-body pb-0 mb-0">
                    <form method="POST" action="{{ route('salarystructures.store') }}" onsubmit="submitStructure(event)">
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:170px'>{{ __('Structure Name') }}</span>
                                </div>
                                <input 
                                    id="structurename" 
                                    type="text" 
                                    class="form-control"
                                    name="structurename"
                                    value=""
                                    required>

                                @if ($errors->has('structurename'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('structurename') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @foreach($config as $index => $field)
                         
                         <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:170px'>{{$field->param_uf_name}}</span>
                                </div>
                                <input 
                                    id="{{$field->param_name}}"
                                    name="{{$field->param_name}}"
                                    type="text" 
                                    class="form-control{{ $errors->has($field->param_name) ? ' is-invalid' : '' }}" 
                                    value="{{ $field->value }}"
                                    required>

                                @if ($errors->has($field->param_name))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first($field->param_name) }}</strong>
                                    </span>
                                @endif
                                <script>
                                root[{{$index}}] = new Fields("{{$field->param_name}}", "{{$field->param_uf_name}}", 0);
                                </script>
                            </div>
                        </div>
                        
                        @endforeach
                        <div class='form-group row mt-2'>
                            <div class="input-group input-group-sm col-md-6">
                                <input type="submit" class="btn btn-outline-primary btn-sm btn-block" value="Create New">
                            </div>
                            <div class="input-group input-group-sm col-md-6">
                            <a href="/salarystructures" class="btn btn-outline-secondary btn-sm btn-block">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="test">asas</div>
@endsection
<script src="{{ asset('js/salarystructure.js') }}"></script>