<div class="card mb-2 shadow-sm h-md-250">
	<div class=" mb-0 bg-white rounded">
		<div class="media text-muted">
			<div class="media-body small">
				<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
					<strong class="text-dark pl-1 pt-1">Choose an existing client</strong>
					<span>or</span>
					<span class='pr-2'>Create a new Client</span>
				</div>
				<div class="d-flex justify-content-center bg-light w-100 border-bottom pt-2 text-dark">
					<select name='report-client' id='report-client' class='form-control form-control-sm ml-2 mr-2 mb-2' onchange='showClientContact(this)'>
					@if($newid != 0)
						<option disabled> Select One</option>
					@else
					<option disabled selected> Select One</option>
					@endif
					@foreach($clients as $client)
					@if($newid != null && $newid == $client->id)
					<option class="text-primary bg-light" value='{{$client->id}}' selected>
					@else
					<option class="text-primary bg-light" value='{{$client->id}}'>
					@endif
						{{$client->organization}}
					</option>
					@endforeach
					</select>
				</div>				
			</div>
		</div>
	</div>
</div>

<div id='client-contacts' style='display:none'>

</div>
