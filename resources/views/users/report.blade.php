@extends('layouts.app')
@section('content')
<div class='row mt-0 pt-0 small w-100'>
	<div class='col-md-3 text-center'>Step 1</div>
	<div class='col-md-3 text-center'>Step 2</div>
	<div class='col-md-3 text-center'>Step 3</div>
	<div class='col-md-3 text-center'>Step 4</div>
</div>

<div class="container-fluid">
	<div class="row" >
		<div class="col-md-6">
			<div class="card mb-4 shadow-sm h-md-250">
				<div class=" mb-0 bg-white rounded">
					<div class="media text-muted">
						<div class="media-body small">
							<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
								<strong class="text-dark pl-1 pt-1">Report Preview</strong>
								<!--<a href="/users/create" class="pr-2 pt-1">Create New User</a> -->
							</div>
							<div class="d-flex justify-content-center bg-light w-100 border-bottom pt-2 text-dark">
								<h5 style='text-decoration:underline'>VISIT REPORT</h5>
							</div>
							<div class=" m-0 pl-2 text-primary">
							<table>
								<tr><td>Name</td><td>:</td><td>{{Auth::User()->fname}}&nbsp;{{Auth::User()->sname}}</td></tr>
								<tr><td>Designation</td><td>:</td><td>{{Auth::User()->designation}}</td></tr>
								<tr><td>Department</td><td>:</td><td>{{Auth::User()->department}}</td></tr>
							<tr><td>Date of submission</td><td>:</td><td>{{date('d-m-Y')}}</td></tr>
						</table>
						</div>
						</div>
					</div>
				</div>
			</div>      
		</div>
	
		<div class="col-md-12 col-lg-6">
			<div class='row'>
				<div class="col-md-5 w-100" id='client-chooser'>
					Choose from an existing client
				</div>
				<div class="col-md-1 d-flex justify-content-center">OR</div>
				<div class="col-md-6" id='client-creator'>
						@include('clients.create')
				</div>
			</div>
		</div>
	</div>
</div>

@endsection