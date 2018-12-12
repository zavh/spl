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
                        <div class="align-items-center text-black-50">
                            <div class='row'>
                                <div class="col-md-12">
                                    <form action="" class='m-0 p-0' autocomplete="off" id="findreports" name='findreports' onsubmit='findReports(event, this)'>
                                        {{-- <div class="input-group input-group-sm">
                                            <strong class="col-md-4">Search by Date:</strong>
                                            <input type="date" name='reportdate' id='reportdate' class="rloinput form-control" value="0"
                                                placeholder="Search Report" aria-label="Report Month" aria-describedby="button-addon2">
                                            <div class="autocomplete"></div>
                                        </div> --}}

                                        <div class="input-group input-group-sm">
                                            <strong class="col-md-4">Search by Date Range:</strong>
                                            <input type="date" name='reportmonthstart' id='reportmonthstart' class="rloinput form-control" value=""
                                                placeholder="Search Report" aria-label="Report Month" aria-describedby="button-addon2">
                                            <div class="autocomplete"></div>
                                            <input type="date" name='reportmonthend' id='reportmonthend' class="rloinput form-control" value=""
                                                placeholder="Search Report" aria-label="Report Month" aria-describedby="button-addon2">
                                            <div class="autocomplete"></div>
                                        </div>

                                        <div class="input-group input-group-sm">
                                            <strong class="col-md-4">Search by Organization:</strong>
                                            <input type="text" name='reportorganization' id='reportorganization' class="rloinput form-control" value=""
                                                placeholder="Search Report" aria-label="Report Organization" aria-describedby="button-addon2">
                                            <div class="autocomplete"></div>
                                        </div>

                                        <div class="input-group input-group-sm">
                                            <strong class="col-md-4">Search by User:</strong>
                                            <input type="text" name='reportuser' id='reportuser' class="rloinput form-control" value=""
                                                placeholder="Search Report" aria-label="Report User" aria-describedby="button-addon2">
                                            <div class="autocomplete"></div>
                                        </div>

                                        <div class="input-group-append">
                                            <button class="btn btn-secondary btn-sm" type="submit" id="button-addon2">Go</button>
                                        </div>
                                        <div class="autocomplete"></div>
                                    </form>
                                </div>
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