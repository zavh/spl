@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    {{ __('Create new project') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('projects.store') }}" style='font-size:10px' name='createproject' id='createproject' onsubmit='createProject(event, this)'>
                        @csrf
                        <!-- Client Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm" style="width:100px">
                                        Client &nbsp;
                                        @empty($preload)
                                            <a href="{{route('createfrom', ['page'=>'project'])}}"
                                                class='btn btn-outline-primary btn-sm' 
                                                style='border-radius:50%;width:15px;height:15px;padding:2px'>
                                                <div style='position:absolute;top:-3px;left:2.3px'>+</div>
                                            </a>
                                        @endempty
                                    </span>
                                </div>
                                @isset($preload)
                                <select name="client_id" id="client_id" class="cpinput form-control{{ $errors->has('client_id') ? ' is-invalid' : '' }}"  onchange="getClient(this)" required>
                                @else
                                <select name="client_id" id="client_id" class="cpinput form-control{{ $errors->has('client_id') ? ' is-invalid' : '' }}"  onchange="getClient(this)" required>
                                @endisset
                                    @isset($preload)
                                        <option disabled value>Select One</div>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}" {{$client_id == $client->id ? 'selected':'disabled'}} > {{$client->organization}}</option>
                                        @endforeach
                                    @else
                                    <option disabled selected value>Select One</div>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}">{{$client->organization}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                    <span class="invalid-feedback" role="alert" id="client_id_error_span">
                                        <strong id="client_id_error">{{ $errors->first('client_id') }}</strong>
                                    </span>
                                    
                            </div>
                        </div>
                        <!-- Client Input Ends -->
                        <!-- Project Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:100px">Project Name</span>
                                </div>
                                <input id="project_name" type="text" class="cpinput form-control{{ $errors->has('project_name') ? ' is-invalid' : '' }}" name="project_name" value="{{ old('project_name') }}" required>

                                    <span class="invalid-feedback" role="alert" id="project_name_error_span">
                                        <strong id="project_name_error">{{ $errors->first('project_name') }}</strong>
                                    </span>
                            </div>
                        </div>
                        <!-- Project Name Input Ends -->
                       <!-- Start Date Input Starts -->
                       <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:100px">Starting Date</span>
                                </div>
                                <input id="start_date" type="date" class="cpinput form-control{{ $errors->has('start_date') ? ' is-invalid' : '' }}" name="start_date" value="{{ old('start_date') }}" 
                                    required>

                                    <span class="invalid-feedback" role="alert" id="start_date_error_span">
                                        <strong id="start_date_error">{{ $errors->first('start_date') }}</strong>
                                    </span>
                            </div>
                        </div>
                        <!-- Start Date Input Ends -->
                        <!-- Deadline Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:100px">Deadline</span>
                                </div>
                                <input id="deadline" type="date" class="cpinput form-control{{ $errors->has('deadline') ? ' is-invalid' : '' }}" name="deadline" value="{{ old('deadline') }}" 
                                    min="<?php echo date('Y-m-d');?>"
                                    required>

                                    <span class="invalid-feedback" role="alert" id="deadline_error_span">
                                        <strong id="deadline_error">{{ $errors->first('deadline') }}</strong>
                                    </span>
                            </div>
                        </div>
                        <!-- Deadline Input Ends -->
                        <div class="form-group row" style='margin-top:5px'>
                            <div class="input-group input-group-sm col-md-12">
                                <button type="submit" class="btn btn-primary btn-block btn-sm">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5" style='display:none' id='cp-supplimentary'></div>
    </div>
</div>
@endsection
<script src="{{ asset('js/projects.js') }}" defer></script>
<script src="{{ asset('js/ajaxformprocess.js') }}" defer></script>

<script type="text/javascript" defer>
    @isset($preload)
        window.addEventListener("load",function(){
            var x = document.getElementById("client_id");
            preload = true;
            preloaded_contacts = JSON.parse("[" + '{{$contacts}}' + "]");
            console.log(preloaded_contacts);
            getClient(x);
        },false);
    @endisset
</script>

