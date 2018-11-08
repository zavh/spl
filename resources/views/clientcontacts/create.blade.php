<div class="media text-muted bg-secondary rounded" id="clientcontact-add">
    <div class="media-body pb-0 mb-0 small lh-125">
        <div class="d-flex justify-content-center w-100">
            <strong class="text-info">New Contact </strong>
        </div>
    </div>
</div>
<div class="media text-muted pt-0 pb-0 border-bottom border-secondary">
	<div class="media-body pb-0 mb-0 small">
		<div class="d-flex justify-content-center align-items-center w-100">
			<form method="POST" action="{{ route('clientcontacts.store') }}" class='m-3 w-100' onsubmit="createContact(event, this);" name='addClientContactForm'>
				@csrf
				<!-- Contact Name Input Starts-->
				<div class="form-group row">
					<div class="input-group input-group-sm"  style='margin-top:-10px'>
						<div class="input-group-prepend">
							<span class="input-group-text">Name</span>
						</div>
						<input id="contact_name" type="text" class=" form-control " name="contact_name" required autofocus>
						<span class="invalid-feedback" role="alert" id='contact_name_error_span'>
							<strong id='contact_name_error'></strong>
						</span>
						
					</div>
				</div>
				<!-- Contact Name Input Ends-->
				<!-- Designation Input Starts-->
				<div class="form-group row">
					<div class="input-group input-group-sm"  style='margin-top:-10px'>
						<div class="input-group-prepend">
							<span class="input-group-text">Designation</span>
						</div>
						<input id="designation" type="text" class=" form-control " name="designation" required>
						<span class="invalid-feedback" role="alert" id='designation_error_span'>
							<strong id='designation_error'></strong>
						</span>
					</div>
				</div>
				<!-- Designation Input Ends-->
				<!-- Contact Input Starts-->
				<div class="form-group row">
					<div class="input-group input-group-sm"  style='margin-top:-10px'>
						<div class="input-group-prepend">
							<span class="input-group-text">Phone</span>
						</div>
						<input id="contact" type="text" class=" form-control " name="contact" required>
						<span class="invalid-feedback" role="alert" id='contact_error_span'>
							<strong id="contact_error"></strong>
						</span>
					</div>
				</div>
				<!-- Contact Input Ends-->
				<!-- Buttons Start-->
				<div style='margin-top:-10px' class="d-flex justify-content-center">
					<!-- <button type="submit" class="btn btn-outline-primary btn-sm btn-block">{{ __('Create Task') }}</button> -->
					<button 
						type="submit" 
						class="btn btn-sm btn-outline-primary mr-2"
						>Create</button>
					<button 
						type="button" 
						class="btn btn-sm btn-outline-secondary ml-2" 
						onclick="ajaxFunction('cancelContactAdd', '{{$client_id}}' , 'clientcontact-add')"
						>Cancel</button>
				</div>
				<!-- Buttons End-->
				<input type="hidden" name="cc_client_id" id="cc_client_id" value="{{$client_id}}">
			</form>
		</div>
	</div>
</div>