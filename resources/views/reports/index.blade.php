@extends('layouts.app')
@section('content')
<div class='row mx-2'>
    <div class='col-6'>
        <div class="card mb-4 shadow-sm h-md-250">
            <div class=" mb-0 bg-white rounded">
                <div class="media text-muted">
                    <div class="media-body small">
                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                            <strong class="text-dark pl-1 pt-1">List of submitted Reports</strong>
                        </div>
                        @foreach($reports as $report)
                        <div class="row m-0 bg-light border-bottom w-100">
                            <div class="col-md-12 text-primary pl-1 text-secondary">
                                Report for <strong class='text-primary'>{{$report->report_data->client_data->organization}}</strong>
                                By <strong>{{$report->report_data->rc_user_name}}</strong>
                                <div class="d-flex  align-items-center border-top border-success px-2">
                                <a href="/reports/{{$report->id}}" class='text-primary'>View</a> &nbsp;
                                <a href="{{route('rtop',['report_id'=>$report->id])}}" class='text-success'>Convert</a> &nbsp;
                                <a href="javascript:void(0)" class='text-danger'>Reject</a>                      
                            </div>

                            </div>                            
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='col-6'>
        <div class="card mb-4 shadow-sm h-md-250">
            <div class=" mb-0 bg-white rounded">
                <div class="media text-muted">
                    <div class="media-body small">
                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                            <strong class="text-dark pl-1 pt-1">Visit Schedules</strong>
                        </div>
                        @if(count($visits)>0)
                        @foreach($visits as $visit)
                        <div class="row m-0 bg-light border-bottom w-100">
                            <div class="col-md-12 text-primary pl-1 text-secondary">
                                <strong class='text-primary'>{{$visit->report_data->rc_user_name}}</strong>
                                Visiting 
                                <strong class='text-success'>{{$visit->report_data->client_data->organization}}</strong> on
                                @if(isset($visit->report_data->report_data->visit_date))
                                <span class='text-danger'>{{$visit->report_data->report_data->visit_date}}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection