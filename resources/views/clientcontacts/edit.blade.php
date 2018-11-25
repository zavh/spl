<div class="media text-muted bg-secondary rounded m-2">
    <div class="media-body pb-0 mb-0 small lh-125">
        <div class="d-flex justify-content-center w-100">
            <strong class="text-info">Edit Contact</strong>
        </div>
    </div>
</div>
<div class="media text-muted pt-0 pb-0 border-bottom border-secondary m-2">
	<div class="media-body pb-0 mb-0 small">
		<div class="d-flex justify-content-center align-items-center w-100">
			<form method="POST" action="{{ route('clientcontacts.update', [$cc->id]) }}" class='m-3 w-100' onsubmit="editClientValidation(event, this);" name='editClientContactForm' id='editClientContactForm'>
				@csrf
				<input name="_method" type="hidden" value="PUT">
				<input name="clientcontact_id" type="hidden" value="{{$cc->id}}" id="clientcontact_id">
				<!-- Contact Name Input Starts-->
				<div class="form-group row">
					<div class="input-group input-group-sm"  style='margin-top:-10px'>
						<div class="input-group-prepend">
							<span class="input-group-text" style="width:100px">Name</span>
						</div>
						<input id="name" type="text" class=" form-control " name="name" value= '{{$cc->name}}' required autofocus>
						<span class="invalid-feedback" role="alert" id='name_error_span'>
							<strong id='name_error'></strong>
						</span>
						
					</div>
				</div>
				<!-- Contact Name Input Ends-->
				<!-- Designation Input Starts-->
				<div class="form-group row">
					<div class="input-group input-group-sm"  style='margin-top:-10px'>
						<div class="input-group-prepend">
							<span class="input-group-text" style="width:100px">Designation</span>
						</div>
						<input id="designation" type="text" class=" form-control " name="designation" value= '{{$cc->designation}}' required>
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
							<span class="input-group-text" style="width:100px">Phone</span>
						</div>
						<input id="contact" type="text" class=" form-control " name="contact" value= '{{$cc->contact}}' required>
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
						>Edit</button>
					<button 
						type="button" 
						class="btn btn-sm btn-outline-secondary ml-2" 
						onclick="document.getElementById('client-contact-edit').innerHTML=''"
						>Cancel</button>
				</div>
				<!-- Buttons End-->
			</form>
		</div>
	</div>
</div>