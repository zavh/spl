@extends('layouts.app')
@section('content')
{{-- <!--Report search starts-->
<div class='row mx-2'>
    <div class='col-6'>
        <div class="card mb-4 shadow-sm h-md-250">
            <div class=" mb-0 bg-white rounded">
                <div class="media text-muted">
                    <div class="media-body small">
                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                            <strong class="text-dark pl-1 pt-1">List of submitted Reports</strong>
                        </div>
                        <form autocomplete="off" id="findreports" name='findreports' onsubmit='findReports(event, this)'>
                            <div class="autocomplete">
                            <input id="reportingOrganization" name="reportingOrganization" type="text" class="rloinput form-control form-control-sm" 
                                    placeholder="Search Report" aria-label="Report Name" aria-describedby="button-addon2">
                            </div>
                            <button class="btn btn-outline-secondary btn-sm" type="submit" id="button-addon2">Go</button>
                        </form>
                        <script>
                            var x = '{{$x}}' ;
                            var names = x.split(',');
                            var inp = document.getElementById("reportingOrganization");
                            autocomplete(document.getElementById("reportingOrganization"), names);
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>--}}
<!--Report search ends--> 

<div class='row mx-2'>
    <div class='col-6'>
        <div class="card mb-4 shadow-sm h-md-250">
            <div class=" mb-0 bg-white rounded">
                <div class="media text-muted">
                    <div class="media-body small">
                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                            <strong class="text-dark pl-1 pt-1">Submitted Reports by month</strong>
                        </div>
                        <div class="col-md-12 text-primary pl-1 text-secondary">
                            <div class="accordion" id="reportcurrentAccordion">
                                @foreach ($current_month_report as $month=>$dates)
                                    <div id='contact-item-{{$month}}'>
                                        <div class="media text-muted" id="heading{{$month}}">
                                            <div class="media-body pb-0 mb-0 small lh-125">
                                                <div class="d-flex justify-content-between align-items-center w-100 border-bottom mb-0 pb-0 pr-2 bg-light">
                                                    <button 
                                                        class="btn btn-link btn-sm" 
                                                        type="button" 
                                                        data-toggle="collapse" 
                                                        data-target="#collapse{{$month}}" 
                                                        aria-expanded="false" 
                                                        aria-controls="collapse{{$month}}" 
                                                        >
                                                    {{$month}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="collapse{{$month}}" class="collapse" aria-labelledby="heading{{$month}}" data-parent="#reportcurrentAccordion">
                                            @foreach ($dates as $date=>$value)
                                                <div class="small ml-4 mr-4 border-bottom text-success">
                                                    <strong>Date: {{$value['data']->report_data->visit_date}}</strong>
                                                    <strong><a href="/reports/{{$value['id']}}" class='text-primary'>View</a> &nbsp;</strong>
                                                    {{-- <a href="{{route('rtop',['report_id'=>$report->id])}}" class='text-success'>Convert</a> &nbsp;
                                                    <a href="javascript:void(0)" class='text-danger'>Reject</a>   --}}
                                                </div>
                                                <div class="small ml-4 mr-4 border-bottom text-success">
                                                    Issue: <span >{{$value['data']->report_data->meeting_issue}}</span>
                                                    visited by: <span >{{$value['data']->rc_user_name}}</span>
                                                    of organization : <span >{{$value['data']->client_data->organization}}</span>
                                                </div>
                                            @endforeach
                                        </div> 
                                    </div>
                                @endforeach
                            </div>      
                        </div>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>
                            
{{-- <div class="row m-0 bg-light border-bottom w-100">
    <div class="col-md-12 text-primary pl-1 text-secondary">
        Report for <strong class='text-primary'>{{$report->report_data->client_data->organization}}</strong>
        By <strong>{{$report->report_data->rc_user_name}}</strong>
        <div class="d-flex  align-items-center border-top border-success px-2">
        <a href="/reports/{{$report->id}}" class='text-primary'>View</a> &nbsp;
        <a href="{{route('rtop',['report_id'=>$report->id])}}" class='text-success'>Convert</a> &nbsp;
        <a href="javascript:void(0)" class='text-danger'>Reject</a>                      
    </div>

    </div>                            
</div> --}}
<div class='row mx-2'>
    <div class='col-6'>
        <div class="card mb-4 shadow-sm h-md-250">
            <div class=" mb-0 bg-white rounded">
                <div class="media text-muted">
                    <div class="media-body small">
                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                            <strong class="text-dark pl-1 pt-1">Visit Schedules</strong>
                        </div>
                        @foreach($visits as $visit)
                        <div class="row m-0 bg-light border-bottom w-100">
                            <div class="col-md-12 text-primary pl-1 text-secondary">
                                <strong class='text-primary'>{{$visit->report_data->rc_user_name}}</strong>
                                Visiting 
                                <strong class='text-success'>{{$visit->report_data->client_data->organization}}</strong> on
                                <span class='text-danger'>{{$visit->report_data->report_data->visit_date}}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--list of reports by month-->
<div class='row mx-2'>
    <div class='col-6'>
        <div class="card mb-4 shadow-sm h-md-250">
            <div class=" mb-0 bg-white rounded">
                <div class="media text-muted">
                    <div class="media-body small">
                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                            <strong class="text-dark pl-1 pt-1">Submitted Reports by month</strong>
                        </div>
                        <div class="col-md-12 text-primary pl-1 text-secondary">
                            <div class="accordion" id="reportdateAccordion">
                                @foreach ($report_of_month as $month=>$dates)
                                {{-- @foreach($contacts as $index=>$contact) --}}
                                    <div id='contact-item-{{$month}}'>
                                        <div class="media text-muted" id="heading{{$month}}">
                                            <div class="media-body pb-0 mb-0 small lh-125">
                                                <div class="d-flex justify-content-between align-items-center w-100 border-bottom mb-0 pb-0 pr-2 bg-light">
                                                    <button 
                                                        class="btn btn-link btn-sm" 
                                                        type="button" 
                                                        data-toggle="collapse" 
                                                        data-target="#collapse{{$month}}" 
                                                        aria-expanded="false" 
                                                        aria-controls="collapse{{$month}}" 
                                                        >
                                                    {{$month}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="collapse{{$month}}" class="collapse" aria-labelledby="heading{{$month}}" data-parent="#reportdateAccordion">
                                            @foreach ($dates as $date=>$value)
                                                @for ($i = 0; $i < count($value); $i++)
                                                        <div class="small ml-4 mr-4 border-bottom text-success">
                                                            <strong>Date: {{$value[$i]['data']->report_data->visit_date}}</strong>
                                                            <strong><a href="/reports/{{$value[$i]['id']}}" class='text-primary'>View</a> &nbsp;</strong>
                                                            {{-- <a href="{{route('rtop',['report_id'=>$report->id])}}" class='text-success'>Convert</a> &nbsp;
                                                            <a href="javascript:void(0)" class='text-danger'>Reject</a>  --}}
                                                        </div>
                                                        <div class="small ml-4 mr-4 border-bottom text-success">
                                                            Issue: <span >{{$value[$i]['data']->report_data->meeting_issue}}</span>
                                                            visited by: <span >{{$value[$i]['data']->rc_user_name}}</span>
                                                            of organization : <span >{{$value[$i]['data']->client_data->organization}}</span>
                                                        </div>
                                                @endfor
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>  
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection