@extends('layouts.app')
@section('content')
<nav aria-label="breadcrumb" style='font-size:12px'>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="/projects">Enquiries</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Enquiry</li>
  </ol>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-header text-white bg-primary">
                    {{ __('Create new Enquiry') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('enquiries.store') }}" style='font-size:10px' name='createenquiries' id='createenquiries' onsubmit='createEnquiries(event, this)'>
                    <input type="hidden" name="project_id" class="ceinput" value="{{$project_id}}">
                        @csrf
                        <!-- Pump Type Selection Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12" style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">
                                        Pump Type
                                    </span>
                                </div>
                                <select name="type" 
                                        id="type" 
                                        class="ceinput form-control{{ $errors->has('type') ? ' is-invalid' : '' }}"
                                        required
                                        aria-label="pump type"
                                        aria-describedby="inputGroup-sizing-sm"
                                        onchange = "showEnqInputs(this)"
                                        >
                                    <option disabled selected>Select One</option>
                                    <option value='surface'>Surface</option>
                                    <option value='submerse'>Submersible</option>
                                </select>
                            </div>

                            <span class="invalid-feedback" role="alert" id="type_error_span">
                                <strong id="type_error">{{ $errors->first('type') }}</strong>
                            </span>
                        </div>
                        <!-- Pump Type Selection Ends -->
                        <!-- Surface Pump Type Selection Starts -->
                        <div class="form-group row surface-row" style='margin-top:-10px;display:none'>
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" id="inputGroup-sizing-sm">
                                        <input 
                                            type="radio" 
                                            name='surftype' 
                                            required 
                                            disabled 
                                            class="ceinput surface-row-el"
                                            value="recirculating">
                                    </div>
                                </div>
                                <input type="text" class="form-control" disabled value='Recirculating Water'>
                            </div>
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" id="inputGroup-sizing-sm">
                                        <input 
                                            type="radio" 
                                            name='surftype' 
                                            required 
                                            disabled 
                                            class="ceinput surface-row-el"
                                            value="lifting">
                                    </div>
                                </div>
                                <input type="text" class="form-control" disabled value='Lifting'>
                            </div>

                            <span class="invalid-feedback" role="alert" id="surftype_error_span">
                                <strong id="surftype_error">{{ $errors->first('surftype') }}</strong>
                            </span>
                        </div>
                        <!-- Surface Pump Type Selection Ends -->
                        <!-- Submersible Pump Type Selection Starts -->
                        <div class="form-group row submerse-row" style='margin-top:-10px;display:none'>
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" id="inputGroup-sizing-sm">
                                        <input 
                                            type="radio" 
                                            name='subtype' 
                                            required 
                                            disabled 
                                            class="ceinput submerse-row-el" 
                                            value='borewell'>
                                    </div>
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with radio button" disabled value='Bore Well'>
                            </div>
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" id="inputGroup-sizing-sm">
                                        <input 
                                            type="radio"
                                            name='subtype' 
                                            required 
                                            disabled 
                                            class="ceinput submerse-row-el"
                                            value='openwell'>
                                    </div>
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with radio button" disabled value='Open Well'>
                            </div>
                            
                            <span class="invalid-feedback" role="alert" id="subtype_error_span">
                                <strong id="subtype_error">{{ $errors->first('subtype') }}</strong>
                            </span>
                        </div>
                        <!-- Submersible Pump Type Selection Ends -->
                        <div class="form-group row" style='margin-top:-10px'>
                            <!-- Pump Head Input Type starts -->
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Head</span>
                                </div>
                                <input 
                                    id="pumphead" 
                                    type="text" 
                                    class="ceinput form-control{{ $errors->has('pumphead') ? ' is-invalid' : '' }}" 
                                    name="pumphead" 
                                    placeholder="In Meter" 
                                    required>
                                    
                                    <span class="invalid-feedback" role="alert" id="pumphead_error_span">
                                        <strong id="pumphead_error">{{ $errors->first('pumphead') }}</strong>
                                    </span>
                            </div>
                            <!-- Pump Head Input Type ends -->
                            <!-- Pump Capacity Input Type starts -->
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Capacity</span>
                                </div>
                                <input 
                                    id="pumpcap" 
                                    type="text" 
                                    class="ceinput form-control{{ $errors->has('pumpcap') ? ' is-invalid' : '' }}" 
                                    name="pumpcap" 
                                    placeholder="In Cubic Meter" 
                                    required>

                                    <span class="invalid-feedback" role="alert" id="pumpcap_error_span">
                                        <strong id="pumpcap_error">{{ $errors->first('pumpcap') }}</strong>
                                    </span>
                            </div>
                            <!-- Pump Capacity Input Type starts -->
                        </div>

                        <div class="form-group row" style='margin-top:-10px'>
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Liquid Name</span>
                                </div>
                                <input 
                                    id="liquid" 
                                    type="text" 
                                    class="ceinput form-control{{ $errors->has('liquid') ? ' is-invalid' : '' }}" 
                                    name="liquid" 
                                    value="{{ old('liquid') }}" 
                                    required>

                                    <span class="invalid-feedback" role="alert" id="liquid_error_span">
                                        <strong id="liquid_error">{{ $errors->first('liquid') }}</strong>
                                    </span>
                            </div>
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Liquid Temperature</span>
                                </div>
                                <input id="liqtemp" type="text" class="ceinput form-control{{ $errors->has('liqtemp') ? ' is-invalid' : '' }}" name="liqtemp" value="{{ old('liqtemp') }}" required>

                                <span class="invalid-feedback" role="alert" id="liqtemp_error_span">
                                    <strong id="liqtemp_error">{{ $errors->first('liqtemp') }}</strong>
                                </span>
                            </div>                            
                        </div>

                        <div class="input-group" style='margin-top:-10px'>
                            <div class="input-group-prepend">
                                <span class="input-group-text" style='font-size:12px'>Description</span>
                            </div>
                            <textarea class="ceinput form-control" aria-label="With textarea" name='description' required></textarea>
                        </div>

                        <div class="form-group row" style='margin-top:5px'>
                            <div class="input-group input-group-sm col-md-12">
                                <button type="submit" class="btn btn-primary btn-block btn-sm">
                                    {{ __('Create') }}
                                </button>
                            </div>
                            <span class="invalid-feedback" role="alert" id="description_error_span">
                                <strong id="description_error">{{ $errors->first('description') }}</strong>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5" style='display:none' id='cp-supplimentary'></div>
    </div>
</div>
@endsection
<script src="{{ asset('js/projects.js?version=0.2') }}" defer></script>

<script src="{{ asset('js/ajaxformprocess.js') }}" defer></script>