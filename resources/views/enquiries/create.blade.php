@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="border-bottom">
                     <span class='px-2'>{{ __('Create new Enquiry') }}</span>
                </div>

                <div class="mx-2 mt-2">
                    <form method="POST" action="{{ route('enquiries.store') }}" style='font-size:10px'>
                    <input type="hidden" name="project_id" value="{{$project_id}}">
                        @csrf
                        <!-- Pump Type Selection Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm" style="width:100px">
                                        Pump Type
                                    </span>
                                </div>
                                <select name="type" 
                                        id="type" 
                                        class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}"
                                        required
                                        aria-label="pump type"
                                        aria-describedby="inputGroup-sizing-sm"
                                        onchange = "showEnqInputs(this)"
                                        >
                                    <option disabled selected>Select One</div>
                                    <option value='surface'>Surface</div>
                                    <option value='submerse'>Submersible</div>
                                </select>
                            </div>
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
                                            class="surface-row-el"
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
                                            class="surface-row-el"
                                            value="lifting">
                                    </div>
                                </div>
                                <input type="text" class="form-control" disabled value='Lifting'>
                            </div>
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
                                            class="submerse-row-el" 
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
                                            class="submerse-row-el"
                                            value='openwell'>
                                    </div>
                                </div>
                                <input type="text" class="form-control" aria-label="Text input with radio button" disabled value='Open Well'>
                            </div>
                        </div>
                        <!-- Submersible Pump Type Selection Ends -->
                        <div class="form-group row" style='margin-top:-10px'>
                            <!-- Pump Head Input Type starts -->
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:100px">Head</span>
                                </div>
                                <input 
                                    id="pumphead" 
                                    type="text" 
                                    class="form-control{{ $errors->has('pumphead') ? ' is-invalid' : '' }}" 
                                    name="pumphead" 
                                    placeholder="In Meter" 
                                    required>
                                @if ($errors->has('pumphead'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pumphead') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- Pump Head Input Type ends -->
                            <!-- Pump Capacity Input Type starts -->
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:100px">Capacity</span>
                                </div>
                                <input 
                                    id="pumpcap" 
                                    type="text" 
                                    class="form-control{{ $errors->has('pumpcap') ? ' is-invalid' : '' }}" 
                                    name="pumpcap" 
                                    placeholder="In Cubic Meter" 
                                    required>

                                @if ($errors->has('pumpcap'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pumpcap') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- Pump Capacity Input Type starts -->
                        </div>

                        <div class="form-group row" style='margin-top:-10px'>
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:100px">Liquid Name</span>
                                </div>
                                <input 
                                    id="liquid" 
                                    type="text" 
                                    class="form-control{{ $errors->has('liquid') ? ' is-invalid' : '' }}" 
                                    name="liquid" 
                                    value="{{ old('liquid') }}" 
                                    required>

                                @if ($errors->has('liquid'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('liquid') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="input-group input-group-sm col-md-6">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:100px">Temperature</span>
                                </div>
                                <input id="liqtemp" type="text" class="form-control{{ $errors->has('liqtemp') ? ' is-invalid' : '' }}" name="liqtemp" value="{{ old('liqtemp') }}" required>

                                @if ($errors->has('liqtemp'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('liqtemp') }}</strong>
                                    </span>
                                @endif
                            </div>                            
                        </div>

                        <div class="input-group" style='margin-top:-10px'>
                            <div class="input-group-prepend">
                                <span class="input-group-text" style='font-size:12px;width:100px'>Description</span>
                            </div>
                            <textarea class="form-control" aria-label="With textarea" name='description' required></textarea>
                        </div>

                        <div class="form-group row mt-2 mb-0">
                                <div class="input-group input-group-sm col-md-6">
                                    <button type="submit" class="btn btn-outline-primary btn-block btn-sm">
                                        Create
                                    </button>  
                                </div>
                                <div class="input-group input-group-sm col-md-6">
                                    <a href="/projects/{{$project_id}}" class="btn btn-outline-secondary btn-block btn-sm">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/projects.js?version=0.1') }}" defer></script>
