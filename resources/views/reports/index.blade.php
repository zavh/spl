@extends('layouts.app')
@section('content')
<div class='row mx-2'>
    <div class='col-6'>
    <!-- Current month reports Starts -->
        @if($current_month_report != null)
        <div class='row' id="day-wise">
            @include('reports.showreportlist',['current_month_report'=>$current_month_report])
        </div>
        @endif
    <!-- Current month reports Ends -->
    <!-- Report Search Area Starts-->
        <div class="card mb-4 shadow-sm h-md-250">
            <div class=" mb-0 bg-white rounded">
                <div class="media text-muted">
                    <div class="media-body small">
                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                            <strong class="text-dark pl-1 pt-1">Search Reports</strong>
                        </div>
                        <div class="align-items-center text-black-50">
                            <div class='row'>
                                <div class="col-md-12">
                                    <form action="" class='m-0 p-0' autocomplete="off" id="findreports" name='findreports' onsubmit='findReports(event, this)'>
                                        <div class="input-group input-group-sm">
                                            <strong class="col-md-4">Search by Date Range:</strong>
                                            <div class="input-group input-group-sm row-md-6 col-md-4">
                                                <input type="date" name='reportmonthstart' id='reportmonthstart' class="rloinput form-control"
                                                placeholder="Start Date" onchange="dateSearchCriteria(this,1)">
                                            </div>

                                                <span class="invalid-feedback" role="alert" id="reportmonthstart_error_span">
                                                    <strong id="reportmonthstart_error"></strong>
                                                </span>

                                            <div class="input-group input-group-sm row-md-6 col-md-4">
                                                <input type="date" class="form-control" value="" placeholder="End Date" id='dummyreportmonthend' onchange="dateSearchCriteria(this,0)">
                                            <input type="hidden" name='reportmonthend' id='reportmonthend' class="rloinput form-control">
                                            </div>

                                                <span class="invalid-feedback" role="alert" id="reportmonthend_error_span">
                                                    <strong id="reportmonthend_error"></strong>
                                                </span>
                                        </div>

                                        <div class="input-group input-group-sm">
                                            <strong class="col-md-4">Search by Organization:</strong>
                                            <input type="text" name='reportorganization' id='reportorganization' class="rloinput form-control" value=""
                                                placeholder="Search Report" aria-label="Report Organization" aria-describedby="button-addon2">
                                            
                                        </div>
                                            <span class="invalid-feedback" role="alert" id="reportorganization_error_span">
                                                <strong id="reportorganization_error"></strong>
                                            </span>

                                            
                                        <div class="input-group input-group-sm">
                                            <strong class="col-md-4">Search by User:</strong>
                                            <input type="text" name='reportuser' id='reportuser' class="rloinput form-control" value=""
                                                placeholder="Search Report" aria-label="Report User" aria-describedby="button-addon2">
                                                    
                                        </div>
                                            <span class="invalid-feedback" role="alert" id="reportuser_error_span">
                                                <strong id="reportuser_error"></strong>
                                            </span>
                                       
                                            <div class="input-group-append">
                                            <button class="btn btn-secondary btn-sm" type="submit" id="button-addon2">Go</button>
                                        </div>
                                        {{-- <span class="invalid-feedback" role="alert" id="noinput_error_span">
                                            <strong id="noinput_error"></strong>
                                        </span> --}}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Report Search Area Ends-->
        <div class="card mb-4 shadow-sm h-md-250"> <!-- Div 2 -->
            <div class=" mb-0 bg-white rounded"> <!-- Div 3 -->
                <div class="media text-muted"> <!-- Div 4-->
                    <div class="media-body small"><!-- Div 5 - media body div-->
                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">		
                            <strong class="text-dark pl-1 pt-1">Submitted Reports by month</strong>		
                        </div>
                        <div class="col-12 text-primary p-0 text-secondary"> <!-- DIv 6-->
                            <div class="accordion" id="reportdateAccordion"> <!-- DIv 7 Accordion -->
                                @foreach ($report_of_month as $month=>$dates)
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
                                                         <div class="ml-4 mr-4 border-bottom text-success">		
                                                             Issue: <span >{{$value[$i]['data']->report_data->meeting_issue}}</span>		
                                                             visited by: <span >{{$value[$i]['data']->rc_user_name}}</span>		
                                                             of organization : <span >{{$value[$i]['data']->client_data->organization}}</span>		
                                                         </div>		
                                                 @endfor		
                                             @endforeach		
                                         </div>
                                    </div>	
                                @endforeach
                            </div><!-- DIv 7 Accordion -->
                        </div><!-- Div 6-->
                    </div><!-- Div 5 media body div-->
                </div><!-- Div 4-->
            </div><!-- Div 3-->
        </div><!-- Div 2-->
    </div> <!-- Left Area / Report Area ends-->

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
                                <span>{{$visit->organization}}</span>
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
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>
<script src="{{ asset('js/report.js') }}"></script>
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>