<div class="row">
	<div class="col-lg-6">
		<!-- Organization/Client Detail Area Starts-->
		<div class=" mb-2 bg-white rounded shadow-sm">
			<div class="media text-muted border rounded" id='client-details'>
				@include('clients.clientdetails', [
					'organization'=>$client->organization, 
					'address'=>$client->address, 
					'client_id'=>$client->id,
					'background'=>$client->background
					])
			</div>
		</div>
		<!-- Organization/Client Detail Area Ends-->
		<!-- Client Area Starts-->
		<div class="bg-white rounded shadow-sm border">
			<div id="clientcontact-add" class='border-bottom w-100'>
				@include('clientcontacts.contactheader', ['client_id'=>$client->id])
			</div>
			<div id='client-contacts'>
				@include('clientcontacts.index', ['contacts'=>$client->contacts])
			</div>
		</div>
		<!-- Client Area Ends-->
			
		<!-- Project Detail Area Starts-->
		<div class="p-3 mt-2 mb-2 bg-white rounded shadow-sm">
		<h6 class="border-bottom border-gray pb-2 mb-0">Projects</h6>
			@if(count($client->projects) == 0)
				<span>No Projects from {{$client->organization}}</span>
			@else
				@foreach($client->projects as $project)
					<div class="media text-muted pt-1">
					@if($project->status == NULL)
						<div class="media-body small bg-light">
					@elseif($project->state == 1)
						<div class="media-body small bg-success">
					@elseif($project->state == 0)
						<div class="media-body small bg-danger">
					@endif
							<div class="d-flex justify-content-between align-items-center w-100">
								<strong class="text-gray-dark">
									Title: <a href="javascript:void(0)" onclick="ajaxFunction('showEnquiries','{{$project->id}}', 'enquiries-content')">{{$project->project_name}}</a>
								</strong>
							</div>
						</div>
					</div>
				@endforeach
			@endif

		<!-- Project Detail Area Ends-->
		</div>
	</div>

	<div class="col-lg-6" id="enquiries-content">

	</div>
</div>