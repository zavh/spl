<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sigma Pumps Limited') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/smallB.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/commons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Sigma Pumps Limited') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto small">
                        <!-- Authentication Links -->
                        @guest
                        @else
                            @php
                                $path = explode('/',Request::path());
                                $thispath = $path[0];
                            @endphp
                            <li class="nav-item">
                            
                            @if($thispath=='home')
                                <a class="nav-link active-nav active" href="/home">Dashboard</a>
                            @else
                                <a class="nav-link" href="/home">Dashboard</a> 
                            @endif
                            </li>
                            <li class="nav-item">
                            @if($thispath=='reports')
                                <a class="nav-link active-nav active" href="/reports">Reports</a>
                            @else
                                <a class="nav-link" href="/reports">Reports</a>
                            @endif
                            </li>
                            <li class="nav-item">
                            @if($thispath=='projects' || $thispath=='enquiries')
                                <a class="nav-link active-nav active" href="/projects">Projects</a>
                            @else
                                <a class="nav-link" href="/projects">Projects</a>
                            @endif
                            </li>
                            <li class="nav-item">
                            @if($thispath=='clients')
                                <a class="nav-link active-nav active" href="/clients">Clients</a>
                            @else 
                                <a class="nav-link" href="/clients">Clients</a>
                            @endif
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    @if(Auth::user()->role_id == 1)
                                        <a class="dropdown-item" href="{{ url('/users') }}">
                                            {{ __('User Management') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/roles') }}">
                                            {{ __('Role Management') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/departments') }}">
                                            {{ __('Department Management') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/designations') }}">
                                            {{ __('Designation Management') }}
                                        </a>
                                    @endif                                    
                                    <a class="dropdown-item" href="/changepass">
                                        {{ __('Change Password') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        
        @isset($breadcrumb)
        <div>
            <ol class="breadcrumb small p-1 m-0">
                @foreach ($breadcrumb as $item)
                    <li class="breadcrumb-item small {{$item['style']}}">
                        @if ($item['link'] == 'none')
                        {{$item['title']}}
                        @else
                        <a href="{{$item['link']}}">
                            {{$item['title']}}
                        </a>
                        @endif
                    </li>                    
                @endforeach
            </ol>
        </div>
        @endisset

        <main class="py-2">
            @yield('content')
        </main>
        <div style='border-width:3px;position:fixed;padding:7px;bottom:0;right:0;max-width:450px;z-index:999'>
            @include('partial.success')
        </div>
    </div>
</body>
</html>
