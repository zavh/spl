@extends('layouts.app')
@section('content')
<nav aria-label="breadcrumb" style='font-size:12px'>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="/projects">Projects</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Project</li>
  </ol>
</nav>
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
                                    <span class="input-group-text" id="inputGroup-sizing-sm">
                                        Client &nbsp;
                                            <a href="javascript:void(0)"
                                                class='btn btn-outline-primary btn-sm' 
                                                style='border-radius:50%;width:15px;height:15px;padding:2px'
                                                onclick="ajaxFunction('showCreateClient', '', 'cp-supplimentary')">
                                                <div style='position:absolute;top:-3px;left:2.3px'>+</div>
                                            </a>
                                    </span>
                                </div>
                                <select name="client_id" id="client_id" class="cpinput form-control{{ $errors->has('client_id') ? ' is-invalid' : '' }}"  onchange="getClient(this)" required>
                                    <option disabled value>Select One</div>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}">{{$client->organization}}</option>
                                        @endforeach
                                </select>
                                @if ($errors->has('client_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('client_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Client Input Ends -->
                        <!-- Project Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Project Name</span>
                                </div>
                                <input id="project_name" type="text" class="cpinput form-control{{ $errors->has('project_name') ? ' is-invalid' : '' }}" name="project_name" value="{{ old('project_name') }}" required>

                                @if ($errors->has('project_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('project_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Project Name Input Ends -->
                        <!-- Deadline Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Deadline</span>
                                </div>
                                <input id="deadline" type="date" class="cpinput form-control{{ $errors->has('deadline') ? ' is-invalid' : '' }}" name="deadline" value="{{ old('deadline') }}" 
                                    min="<?php echo date('Y-m-d');?>"
                                    required>
                                @if ($errors->has('deadline'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('deadline') }}</strong>
                                    </span>
                                @endif
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
<script src="{{ asset('js/projects.js?version=0.1') }}" defer></script>
<script src="{{ asset('js/ajaxformprocess.js') }}" defer></script>
