@extends('layouts.app')

@section('content')
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @isset(Auth::user()->role_id)
                        @if(Auth::user()->role_id == 1)
							<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body ">
                            <div class="row">
                                <div class="col-lg-4 text-center">
                                    <a href='/projects'>
                                            <img class="rounded-circle" src="{{ asset('img/icon.project.png') }}" alt="Generic placeholder image" width="140" height="140" style='background:#eee'>
                                    </a>

                                    <h2>Projects</h2>                         
                                </div><!-- /.col-lg-4 -->
                                <div class="col-lg-4 text-center">
                                    <a href='/reports'>
                                        <img class="rounded-circle" src="{{ asset('img/icon.role.png') }}" alt="Generic placeholder image" width="140" height="140">
                                    </a>
                                    <h2>Visit Reports</h2>
                                </div><!-- /.col-lg-4 -->
                                <div class="col-lg-4 text-center">
                                    <a href='/clients'>
                                        <img class="rounded-circle" src="{{ asset('img/icon.user.png') }}" alt="Generic placeholder image" width="140" height="140" style='background:#eee'>
                                    </a>
                                    <h2>Clients</h2>
                                </div><!-- /.col-lg-4 -->
                            </div>
							                </div>
            </div>
        </div>
    </div>
</div>
                        @else 
                            @include('users.dashboard')
                        @endif
                    @endisset


@endsection
<script src="{{ asset('js/users.js?version=0.1') }}"></script>