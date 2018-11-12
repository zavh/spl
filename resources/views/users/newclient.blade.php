<div class="container-fluid p-0 m-0">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card shadow-sm">
				<div class="d-flex justify-content-between align-items-center w-100 border-bottom small">
					<strong class="text-dark pl-1 pt-1">Create New Client</strong>
				</div>
                <div class="card-body">
                    <form method="POST" action="" style='font-size:10px' onsubmit="newClientValidation(event, this);" name='reportClientCreation' id='reportClientCreation'>
                        @csrf
                        <!-- Organization Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12" style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Organization') }}</span>
                                </div>
                                <input id="organization" type="text" class="form-control " name="organization" required>
								<span class="invalid-feedback " role="alert" id='organization_error_span'>
									<strong id='organization_error'></strong>
								</span>
                            </div>
                        </div>
                        <!-- Organization Name Input Ends -->
                        <!-- Address Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Address') }}</span>
                                </div>
                                <input id="address" type="text" class="form-control" name="address" required>
								<span class="invalid-feedback " role="alert" id='address_error_span'>
									<strong id='address_error'></strong>
								</span>
                            </div>
                        </div>                    
                        <!-- Address Input Ends -->                        
                        <!-- Contact Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Contact Name') }}</span>
                                </div>
                                <input id="name" type="text" class="form-control" name="name" required>
								<span class="invalid-feedback " role="alert" id='name_error_span'>
									<strong id='name_error'></strong>
								</span>
                            </div>
                        </div>
                        <!-- Contact Name Input Ends -->
                        <!-- Contact Designation Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Designation') }}</span>
                                </div>
                                <input id="designation" type="text" class="form-control" name="designation" required>
								<span class="invalid-feedback " role="alert" id='designation_error_span'>
									<strong id='designation_error'></strong>
								</span>
                            </div>
                        </div>
                        <!-- Contact Designation Input Ends -->                        
                        <!-- Client Contact Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Contact Number') }}</span>
                                </div>
                                <input type="text" class="form-control" name="contact" id='contact' required>
								<span class="invalid-feedback " role="alert" id='contact_error_span'>
									<strong id='contact_error'></strong>
								</span>                                
                            </div>
                        </div>
                        <!-- Client Contact Input Ends -->                    
                        <div class='row justify-content-center'> 
                            <input type="submit" class="btn btn-outline-primary btn-sm" value="Create New">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>