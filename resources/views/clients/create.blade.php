@extends('layouts.app')
@section('content')
 <main class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Create new Client</div>

                    <div class="card-body">
                    <form method="POST" action="{{ route('clients.store') }}">
                        @csrf           
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="organization" class="col-sm-4 col-form-label text-md-right">{{ __('Organization') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('organization') ? ' is-invalid' : '' }}" name="organization" value="{{ old('organization') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-4 col-form-label text-md-right">{{ __('Address') }}</label>
                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('organization') }}" required>
                            </div>
                        </div>
                        <input name="_method" type="hidden" value="PUT">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Create Client') }}
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <a href='/clients'>Client Management</a>
            </div>
        </div>
    </div>
 </main>
@endsection