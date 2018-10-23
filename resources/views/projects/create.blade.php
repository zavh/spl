@extends('layouts.app')
@section('content')
<<<<<<< HEAD
<nav aria-label="breadcrumb" style='font-size:12px'>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="/users">Projects</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Project</li>
  </ol>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-white bg-primary">
                    {{ __('Create new project') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="client_id" class="col-md-4 col-form-label text-md-right">{{ __('Client') }}</label>
                            <div class="col-md-6">
                                <select name="client_id" id="client_id" class="form-control{{ $errors->has('client_id') ? ' is-invalid' : '' }}" required autofocus onchange="getClient(this)">
                                <option disabled selected>Select One</div>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                @endforeach
                                </select>
                                @if ($errors->has('client_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('client_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-2">
                            <a href="javascript:void(0)" class="btn btn-sm btn-block btn-outline-primary" style='width:30px;height:30px;border-radius:50%;' onclick="ajaxFunction('showCreateClient', '', 'cp-supplimentary')">+</a>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="project_name" class="col-md-4 col-form-label text-md-right">{{ __('Project Name') }}</label>
                            <div class="col-md-6">
                                <input id="project_name" type="text" class="form-control{{ $errors->has('project_name') ? ' is-invalid' : '' }}" name="project_name" value="{{ old('project_name') }}" required>

                                @if ($errors->has('project_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('project_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Project Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" type="email" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') }}" required >
                                </textarea>

                                @if ($errors->has('description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="deadline" class="col-md-4 col-form-label text-md-right">{{ __('Deadline') }}</label>

                            <div class="col-md-6">
                                <input id="deadline" type="date" class="form-control{{ $errors->has('deadline') ? ' is-invalid' : '' }}" name="deadline" required>

                                @if ($errors->has('deadline'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('deadline') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4" style='display:none' id='cp-supplimentary'></div>
    </div>
</div>
@endsection
<script src="{{ asset('js/projects.js') }}" defer></script>
=======
 <main class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Create new Project</div>
                    <div class="card-body">
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf           
                        <div class="form-group row">
                            <label for="project_name" class="col-sm-4 col-form-label text-md-right">{{ __('project_name') }}</label>
                            <div class="col-md-6">
                                <input id="project_name" type="text" class="form-control{{ $errors->has('project_name') ? ' is-invalid' : '' }}" name="project_name" value="{{ old('name') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="client_id" class="col-sm-4 col-form-label text-md-right">{{ __('client_id') }}</label>
                            <div class="col-md-6">
                                {{-- <input id="client_id" type="number" class="form-control{{ $errors->has('client_id') ? ' is-invalid' : '' }}" name="client_id" value="{{ old('organization') }}" required> --}}
                                <select id="client_id" name="client_id" class="form-control">
                                    @foreach ( $clients as $client)
                                        <option value="{{$client->id}}">{{$client->name}}</option>
                                    @endforeach
                                </select>
                                <a href="/clients/create">Create Client</a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="user_id" class="col-sm-4 col-form-label text-md-right">{{ __('user_id') }}</label>
                            <div class="col-md-6">
                                {{-- <input id="user_id" type="number" class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" name="user_id" value="{{ old('organization') }}" required> --}}
                                <select id="user_id" name="user_id" class="form-control">
                                    @foreach ( $users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                                <a href="/users/create">Create User</a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="manager_id" class="col-sm-4 col-form-label text-md-right">{{ __('manager_id') }}</label>
                            <div class="col-md-6">
                                {{-- <input id="manager_id" type="number" class="form-control{{ $errors->has('manager_id') ? ' is-invalid' : '' }}" name="manager_id" value="{{ old('name') }}" required autofocus> --}}
                                <select id="manager_id" name="manager_id" class="form-control">
                                    @foreach ( $users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="assigned" class="col-sm-4 col-form-label text-md-right">{{ __('assigned') }}</label>
                            <div class="col-md-6">
                                <input id="assigned" type="date" class="form-control{{ $errors->has('assigned') ? ' is-invalid' : '' }}" name="assigned" value="{{ old('assigned') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="deadline" class="col-sm-4 col-form-label text-md-right">{{ __('deadline') }}</label>
                            <div class="col-md-6">
                                <input id="deadline" type="date" class="form-control{{ $errors->has('deadline') ? ' is-invalid' : '' }}" name="deadline" value="{{ old('deadline') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-4 col-form-label text-md-right">{{ __('description') }}</label>
                            <div class="col-md-6">
                                <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('organization') }}" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Create Project') }}
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <a href='/projects'>Project Management</a>
            </div>
        </div>
    </div>
 </main>
@endsection
>>>>>>> 8cc324f9e7482648ce736a1dd246b6635502d2dd
