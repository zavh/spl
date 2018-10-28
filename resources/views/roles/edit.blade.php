@extends('layouts.app')
@section('content')

<main class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Create new role</div>

                    <div class="card-body">
                        <form method='post' action="{{route('roles.update', [$roles->id])}}" style='width:100%'>
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="put">
                            <div class="form-group row">
                                <label for="role_name" class="col-sm-4 col-form-label text-md-right">Role Name</label>
                                <div class="col-md-6">
                                    <input id="role_name" class="form-control{{ $errors->has('role_name') ? ' is-invalid' : '' }}" name="role_name" value="{{ $roles->role_name }}" required="" autofocus="" type="text">
                                                                
                                    @if ($errors->has('role_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('role_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="role_description" class="col-md-4 col-form-label text-md-right">Role Description</label>

                                <div class="col-md-6">
                                    <input id="role_description" class="form-control{{ $errors->has('role_description') ? ' is-invalid' : '' }}" name="role_description" value="{{ $roles->role_description }}"required="" type="textarea">
                                
                                    @if ($errors->has('role_description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('role_description') }}</strong>
                                    </span>
                                    @endif
                            </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <a href='/roles'>Roles Management</a>
            </div>
        </div>
    </div>
</main>

@endsection