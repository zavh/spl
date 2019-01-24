@extends('layouts.app')
@section('content')
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
<div class='container-fluid'>
    <div class="card-deck">
    @foreach($menu as $key=>$value)
        @if(view()->exists(strtolower($key).'.widget'))
            <div id='{{$key}}_widget' class="card">
            <script>
                widgetFunction('widgetShow', '{{strtolower($key)}}', '{{$key}}_widget');
            </script>
            </div>
        @endif
    @endforeach
    </div>
</div>
@endsection
<script src="{{ asset('js/users.js?version=0.1') }}"></script>
<script src="{{ asset('js/widget.js') }}"></script>