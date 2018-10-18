@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body ">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @isset(Auth::user()->role_id)
                        <div class="alert alert-primary">
                            User Role has been set
                        </div>
                    @else 
                        <div class="alert alert-danger">
                                No role set for the user!
                        </div>
                    @endisset
                    
                    <div class="row">
                        <div class="col-lg-4 text-center">
                            <a href='/users'>
                            <img class="rounded-circle" src="{{ asset('img/icon.user.png') }}" alt="Generic placeholder image" width="140" height="140">
                            </a>
                            <h2>Manage Users</h2>                            
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-4 text-center">
                            <a href='/roles'>
                                <img class="rounded-circle" src="{{ asset('img/icon.role.png') }}" alt="Generic placeholder image" width="140" height="140">
                            </a>
                            <h2>Manage User Roles</h2>
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-4 text-center">
                            <a href='/projects'>
                                <img class="rounded-circle" src="{{ asset('img/icon.project.png') }}" alt="Generic placeholder image" width="140" height="140" style='background:#eee'>
                            </a>
                            <h2>Projects</h2>
                        </div><!-- /.col-lg-4 -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
