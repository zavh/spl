@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-light text-dark m-0 p-1 pl-2">
                    {{ __('Edit Salary Structure') }}
                </div>
                <div class="card-body pb-0 mb-0">
                    <form method="POST" action="{{ route('salarystructures.update', [$sid]) }}" onsubmit="editStructure(event, this)" id="editStructureForm" name="editStructureForm">
                        <input name="_method" type="hidden" value="PUT">
                        <input name="sid" id="sid" type="hidden" value="{{$sid}}">

                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:160px'>{{ __('Structure Name') }}</span>
                                </div>
                                <input id="structurename" type="text" class="form-control" name="structurename" value="{{ $sname }}" required>
                                <span class="invalid-feedback" role="alert" id="structurename_error_span">
                                    <strong id="structurename_error"></strong>
                                </span>
                            </div>
                        </div>
                        
                        @foreach($salarystructure as $index => $field)
                         
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
                                <span class="invalid-feedback" role="alert" id="{{$field->param_name}}_error_span">
                                    <strong id="{{$field->param_name}}_error"></strong>
                                </span>
                                <script>
                                root[{{$index}}] = new Fields("{{$field->param_name}}", "{{$field->param_uf_name}}", "{{$field->value}}");
                                </script>
                            </div>
                        </div>
                        
                        @endforeach

                        <div class='form-group row mt-2'>
                            <div class="input-group input-group-sm col-md-6">
                                <input type="submit" class="btn btn-outline-primary btn-sm btn-block" value="Edit">
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
@endsection
<script src="{{ asset('js/salarystructure.js') }}"></script>
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>