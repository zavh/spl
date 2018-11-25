@extends('layouts.app')
@section('content')
<div class="card mb-4 shadow-sm h-md-250 ml-2 mr-2">
    <div class=" mb-0 bg-white rounded">
        <div class="media text-muted">
            <div class="media-body small">
                <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                    <strong class="text-dark pl-1 pt-1">List of submitted Reports</strong>
                </div>
                @foreach($reports as $report)
                <div class="row m-0 bg-light border-bottom w-100">
                    <div class="col-md-8 text-primary pl-1 text-secondary">
                        Visit Report On <strong class='text-primary'>{{$report->report_data->client_data->organization}}</strong>
                        By <strong>{{$report->report_data->rc_user_name}}</strong>
                    </div>
                    <div class="col-md-4 text-success pl-1 text-success ">
                        <div class="d-flex justify-content-between pt-1">
                            <a href="/reports/{{$report->id}}" class="badge-primary badge padge-pill">View Report</a>
                            <a href="javascript:void(0)" onclick="ajaxFunction('viewuser', '1', 'user-container')" class="badge-success badge padge-pill">Convert to Project</a>
                            <a href="javascript:void(0)" class="badge-danger badge padge-pill">Reject</a>                      
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection