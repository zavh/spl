@extends('layouts.app')
@section('content')
<div class="container-fluid">
	<div class="row" >
		<div class="col-md-12 col-lg-12 pl-3 pr-3">
			<div class="card shadow-sm h-md-250">
				<div class=" mb-0 bg-white rounded">
					<div class="media text-muted">
						<div class="media-body small mb-2">
							<div class="d-flex justify-content-center w-100 border-bottom pt-2 text-dark">
                                <h5 style='text-decoration:underline'>VISIT REPORT</h5>
							</div>
							<!-- Reported Detail Section Starts-->
							<div class="m-0 pl-4 border-bottom">
								<table class="text-primary small">
									<tr><td>Name</td><td>:</td><td>{{$report->rc_user_name}}</td></tr>
									<tr><td>Designation</td><td>:</td><td>{{$report->rc_user_designation}}</td></tr>
									<tr><td>Department</td><td>:</td><td>{{$report->rc_user_department}}</td></tr>
									<tr><td>Date of submission</td><td>:</td><td>{{$dates['submitted']}}</td></tr>
                                    <tr><td>Report created</td><td>:</td><td>{{$dates['submitted']}}</td></tr>
								</table>
							</div>
							<!-- Reported Detail Section Ends-->
							<!-- Client Detail Section Starts-->
							<div class='row m-2 border' id='details-row'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Customer Name & Address</h6>
								</div>
								<div class='col-7'>
									<div id='client-name' class='border-bottom border-success'>
                                    {{$report->client_data->organization}}
                                    </div>
									<div id='client-address' class='pt-0'>
                                    {{$report->client_data->address}}
                                    </div>
								</div>
							</div>
							<!-- Client Detail Section Ends-->
							<!-- Visit Date Section Starts-->
							<div class='row m-2 border' id='visit_date-row'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Visit Date</h6>
								</div>
								<div class='col-7' id='visit_date-details'>
								@isset($report->report_data->visit_date)
                                {{date("d-M-Y",strtotime($report->report_data->visit_date))}}
								@endisset
								</div>
							</div>
							<!-- Visit Date Section Ends-->
							<!-- Client Contact Section Starts-->
							<div class='row m-2 border' id='contact-row'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none;'>Person contacted</h6>
								</div>
								<div class='col-7' id='contact-details'>
                                @foreach($report->client_data->contacts as $index=>$contact)
                                    <div class="border-bottom border-success @if($index>0) mt-2 @endif">Name: {{$contact->name}}</div>
                                    <div class="border-bottom border-success">Designation : {{$contact->designation}}</div>
                                    <div class="none">Phone : {{$contact->contact}}</div>
                                @endforeach
								</div>
							</div>
							<!-- Client Contact Section Ends-->
							<!-- Client Background Section Starts-->
							<div class='row m-2 border' id='background-row'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Company Background</h6>
								</div>
								<div class='col-7' id='background-details'>
                                {{$report->client_data->background}}
								</div>
							</div>
							<!-- Client Background Section Ends-->
							<!-- Meeting Issue Section Starts-->
							<div class='row m-2 border' id='meeting_issue-row'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Meeting Issue</h6>
								</div>
								<div class='col-7' id='meeting_issue-details'>
                                {{$report->report_data->meeting_issue}}
								</div>
							</div>
							<!-- Meeting Issue Section Ends-->
							<!-- Requirement Details Section Starts-->
							<div class='row m-2 border' id='requirement_details-row'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Requirement Details</h6>
								</div>
								<div class='col-7' id='requirement_details-details'>
                                {{$report->report_data->requirement_details}}
								</div>
							</div>
							<!-- Requirement Details Section Ends-->
							<!-- Product Discussed Section Starts-->
							<div class='row m-2 border' id='product_discussed-row'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Product Discussed</h6>
								</div>
								<div class='col-7' id='product_discussed-details'>
                                {{$report->report_data->product_discussed}}
								</div>
							</div>
							<!-- Product Discussed Section Ends-->
							<!-- Outcome in Brief Section Starts-->
							<div class='row m-2 border' id='outcome_brief-row'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Outcome of the visit in brief</h6>
								</div>
								<div class='col-7' id='outcome_brief-details'>
                                {{$report->report_data->outcome_brief}}
								</div>
							</div>
							<!-- Outcome in Brief Section Ends-->
							<!-- Remarks Section Starts-->
							<div class='row m-2 border' id='remarks-row'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Remarks, if any</h6>
								</div>
								<div class='col-7' id='remarks-details'>
                                {{$report->report_data->remarks}}
								</div>
							</div>
							<!-- Remarks Section Ends-->
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>
@endsection