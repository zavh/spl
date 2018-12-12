<div class='col-12'>
        <div class="card mb-4 shadow-sm h-md-250">
            <div class=" mb-0 bg-white rounded">
                <div class="media text-muted">
                    <div class="media-body small">
                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                            <strong class="text-dark pl-1 pt-1">Submitted Reports of current month</strong>
                        </div>
                        <div class="col-md-12 text-primary p-0 text-secondary">
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