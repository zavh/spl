@extends('layouts.app')
@section('content')
@foreach($reports as $report)
    <div class='row'> 
        <div class='col'>{{$report->id}} </div>
        <div class='col'>{{$report->report_data->rc_user_name}} </div>
        <div class='col'>{{htmlspecialchars_decode($report->report_data->rc_user_department)}} </div>
        <div class='col'>{{htmlspecialchars_decode($report->report_data->client_data->organization)}} </div>
        <div class='col'>{{$report->completion}} </div>
    </div>
@endforeach
@endsection