<script src="{{ asset('js/report.js') }}"></script>
@extends('layouts.app')
@section('content')
<div class='row pb-2 small w-100'>
	<div class='col-md-3 text-center' style='border-bottom: 2px solid red'>Step 1</div>
	<div class='col-md-3 text-center text-muted' style='border-bottom: 1px solid #eee'>Step 2</div>
	<div class='col-md-3 text-center text-muted' style='border-bottom: 1px solid #eee'>Step 3</div>
	<div class='col-md-3 text-center text-muted' style='border-bottom: 1px solid #eee'>Step 4</div>
</div>

<div class="container-fluid">
	<div class="row" >
		<div class="col-md-12 col-lg-6 pl-3 pr-3">
			<div class="card mb-4 shadow-sm h-md-250">
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
							<!-- Reported Detail Section Starts-->
							<div class="m-0 pl-4 border-bottom">
								<table class="text-primary small">
									<tr><td>Name</td><td>:</td><td>{{Auth::User()->fname}}&nbsp;{{Auth::User()->sname}}</td></tr>
									<tr><td>Designation</td><td>:</td><td>{{Auth::User()->designation}}</td></tr>
									<tr><td>Department</td><td>:</td><td>{{Auth::User()->department}}</td></tr>
									<tr><td>Date of submission</td><td>:</td><td>{{date('d-M-Y')}}</td></tr>
								</table>
							</div>
							<!-- Reported Detail Section Ends-->
							<!-- Client Detail Section Starts-->
							<div class='row m-2 border' id='client-details' style='display:none'>
								<div class='col-6 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Customer Name & Address</h6>
								</div>
								<div class='col-6'>
									<div id='client-name' class='border-bottom'></div>
									<div id='client-address' class='pt-0'></div>
								</div>
							</div>
							<!-- Client Detail Section Ends-->
							<!-- Client Contact Section Starts-->
							<div class='row m-2 border' id='contact-row' style='display:none'>
								<div class='col-6 border-right'>
									<h6 class='card-header bg-white text-dark' style='border:none'>Person contacted</h6>
								</div>
								<div class='col-6' id='contact-details'>
								</div>
							</div>
							<!-- Client Contact Section Ends-->
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<div class="col-md-12 col-lg-6">
			<div class='row'>
				<div class="col-md-6 w-100 pl-3 pr-3" id='client-chooser'>
					<script>
						ajaxFunction('viewClientList', '' , 'client-chooser');
					</script>
				</div>
				<div class="col-md-6 pl-3 pr-3" id='client-creator'>
					@include('users.newclient')
				</div>
			</div>
		</div>
	</div>
</div>
<script> 
//rc = report creator

report_data['rc_user_id'] = '{{Auth::User()->id}}'; 
report_data['rc_user_name'] = '{{Auth::User()->fname}}' + ' ' + '{{Auth::User()->sname}}'; 
report_data['rc_user_department'] = '{{Auth::User()->department}}'; 
report_data['rc_user_designation'] = '{{Auth::User()->designation}}'; 
</script>
@endsection
