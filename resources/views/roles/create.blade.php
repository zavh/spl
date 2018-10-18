@extends('layouts.app')
@section('content')

<main class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Create new role</div>

                    <div class="card-body">
                        <form method='post' action="{{route('roles.store')}}" style='width:100%'>
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label for="role_name" class="col-sm-4 col-form-label text-md-right">Role Name</label>
                                <div class="col-md-6">
                                    <input id="role_name" class="form-control" name="role_name" value="" required="" autofocus="" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="role_description" class="col-md-4 col-form-label text-md-right">Role Description</label>

                                <div class="col-md-6">
                                    <input id="role_description" class="form-control" name="role_description" required="" type="text">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Create
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