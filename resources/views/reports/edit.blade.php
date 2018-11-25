<script src="{{ asset('js/report.js') }}"></script>
@extends('layouts.app')
@section('content')
<div class="container-fluid">
	<div class="row" >
		<div class="col-md-12 col-lg-8 pl-3 pr-3">
			<div class="card shadow-sm h-md-250">
				<div class=" mb-0 bg-white rounded">
					<div class="media text-muted">
						<div class="media-body small mb-2">
							<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
								<strong class="text-dark pl-1 pt-1">Report Preview</strong>
								<!--<a href="/users/create" class="pr-2 pt-1">Create New User</a> -->
							</div>
							<div class="d-flex justify-content-center bg-light w-100 border-bottom pt-2 text-dark">
								<h5 style='text-decoration:underline'>VISIT REPORT</h5>
							</div>
							<!-- Report Detail Section Starts-->
							<div class="m-0 pl-4 border-bottom">
								<table class="text-primary small">
									<tr><td>Name</td><td>:</td><td>{{$rc_user->fname}}&nbsp;{{$rc_user->sname}}</td></tr>
									<tr><td>Designation</td><td>:</td><td>{{$rc_user->designation->name}}</td></tr>
									<tr><td>Department</td><td>:</td><td>{{$rc_user->department->name}}</td></tr>
									<tr><td>Date of submission</td><td>:</td><td>{{date('d-M-Y')}}</td></tr>
								</table>
							</div>
							<!-- Report Detail Section Ends-->
                            <script>
                                ajaxFunction('clientDetails', '{{$report_id}}' , '');
                            </script>
                            							<!-- Client Detail Section Starts-->
							<div class='row m-2 border' id='details-row' style='display:none'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Customer Name & Address</h6>
								</div>
								<div class='col-7'>
									<div id='client-name' class='border-bottom border-success'></div>
									<div id='client-address' class='pt-0'></div>
								</div>
							</div>
							<!-- Client Detail Section Ends-->
							<!-- Client Contact Section Starts-->
							<div class='row m-2 border' id='contact-row' style='display:none'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none;'>Person contacted</h6>
								</div>
								<div class='col-7' id='contact-details'>
								</div>
							</div>
							<!-- Client Contact Section Ends-->
							<!-- Visit Date Section Starts-->
							<div class='row m-2 border' id='visit_date-row' style='display:none'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Visit Date</h6>
								</div>
								<div class='col-7' id='visit_date-details'>
								</div>
							</div>
							<!-- Visit Date Section Ends-->
							<!-- Client Background Section Starts-->
							<div class='row m-2 border' id='background-row' style='display:none'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Company Background</h6>
								</div>
								<div class='col-7' id='background-details'>
								</div>
							</div>
							<!-- Client Background Section Ends-->
							<!-- Meeting Issue Section Starts-->
							<div class='row m-2 border' id='meeting_issue-row' style='display:none'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Meeting Issue</h6>
								</div>
								<div class='col-7' id='meeting_issue-details'>
								</div>
							</div>
							<!-- Meeting Issue Section Ends-->
							<!-- Requirement Details Section Starts-->
							<div class='row m-2 border' id='requirement_details-row' style='display:none'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Requirement Details</h6>
								</div>
								<div class='col-7' id='requirement_details-details'>
								</div>
							</div>
							<!-- Requirement Details Section Ends-->
							<!-- Product Discussed Section Starts-->
							<div class='row m-2 border' id='product_discussed-row' style='display:none'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Product Discussed</h6>
								</div>
								<div class='col-7' id='product_discussed-details'>
								</div>
							</div>
							<!-- Product Discussed Section Ends-->
							<!-- Outcome in Brief Section Starts-->
							<div class='row m-2 border' id='outcome_brief-row' style='display:none'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Outcome of the visit in brief</h6>
								</div>
								<div class='col-7' id='outcome_brief-details'>
								</div>
							</div>
							<!-- Outcome in Brief Section Ends-->
							<!-- Remarks Section Starts-->
							<div class='row m-2 border' id='remarks-row' style='display:none'>
								<div class='col-5 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Remarks, if any</h6>
								</div>
								<div class='col-7' id='remarks-details'>
								</div>
							</div>
							<!-- Remarks Section Ends-->
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<div class="col-md-12 col-lg-4">
            <div id='stage_1'>
                <div class='row'>
                    <div class="col-md-12 w-100 pl-3 pr-3" id='client-chooser'>
                        <div id='client-contacts' class="card mb-4 shadow-sm">
                        </div>
                    </div>
                </div>                
                <div class="d-flex justify-content-center">
                    <div class="m-4 border border-primary badge badge-pill shadow-sm" id='step1-complete' style='display:none'>
                        <span class='text-primary'> 
                            Save and Proceed 
                            <button class='btn btn-primary rounded-circle' onclick="saveStage(1)">
                            >
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div id='stage_2'>
            </div>
		</div>
	</div>
</div>
<script> 
//rc = report creator

report_data['rc_user_id'] = '{{Auth::User()->id}}'; 
report_data['rc_user_name'] = '{{Auth::User()->fname}}' + ' ' + '{{Auth::User()->sname}}'; 
report_data['rc_user_department'] = '{{Auth::User()->department->name}}'; 
report_data['rc_user_designation'] = '{{Auth::User()->designation->name}}'; 
</script>
@endsection
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>