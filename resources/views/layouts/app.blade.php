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
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel py-0">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Sigma Group') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    @guest
                    @else
                        @include('homes.menu')
                    @endguest
                </div>
            </div>
        </nav>
        {{-- <nav class="navbar navbar-expand-md navbar-light navbar-laravel py-0 my-0">
            <div class="container-fluid small">
                <div class="container-fluid small text-right mt-0">a b c d e f g h</div>
            </div>
        </nav> --}}
        
        {{-- @isset($breadcrumb)
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
        @endisset --}}

        <main class="py-2">
            @yield('content')
        </main>
        <div style='border-width:3px;position:fixed;padding:7px;bottom:0;right:0;max-width:450px;z-index:999'>
            @include('partial.flash')
        </div>
    </div>
</body>
</html>
