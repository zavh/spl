
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="media text-muted border-bottom mt-1 ml-0 mr-0">
                    <div class="media-body small lh-125">
                        <div class="d-flex justify-content-center w-100">
                            <strong class="text-info">Edit Client</strong>
                        </div>
                    </div>
            </div>
            <form method="POST" action="{{route('clients.update', [$assignment->id])}}" onsubmit="return updateClient(event, this)" id="clientEdit" name="clientEdit" class="m-1 p-1">
                @csrf 
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" id="client_id" value="{{$assignment->id}}">
                <div class="form-group row">
					<div class="input-group input-group-sm">
						<div class="input-group-prepend">
							<span class="input-group-text" style='width:140px'>Organization</span>
						</div>
						<input id="organization" type="text" class="form-control" name="organization" value="{{ $assignment->organization }}" required autofocus>
						<span class="invalid-feedback" role="alert" id="organization_error_span">
							<strong id="organization_error"></strong>
						</span>
					</div>
				</div>

                <div class="form-group row">
					<div class="input-group input-group-sm" style="margin-top:-10px">
						<div class="input-group-prepend">
							<span class="input-group-text" style='width:140px'>Address</span>
						</div>
						<input id="address" type="text" class="form-control" name="address" value="{{ $assignment->address }}" required>
						<span class="invalid-feedback" role="alert" id="address_error_span">
							<strong id="address_error"></strong>
						</span>
					</div>
				</div>
                <div class="form-group row">
                    <div class="input-group" style="margin-top:-10px">
                        <div class="input-group-prepend">
                            <span class="input-group-text pl-2" style="font-size:12px;width:140px">Company Background</span>
                        </div>
                        <textarea id="background" class="form-control" aria-label="With textarea" name="background" required=""
                        >{{ $assignment->background }}</textarea>
                        <span class="invalid-feedback " role="alert" id="background_error_span">
                            <strong id="background_error"></strong>
                        </span>
                    </div>
                </div>
                <div style="margin-top:-10px" class="d-flex justify-content-center">
					<button type="submit" class="btn btn-sm btn-outline-primary mr-2">Update</button>
					<button type="button" class="btn btn-sm btn-outline-secondary ml-2" onclick="ajaxFunction('cancelUpdate', '{{$assignment->id}}' , 'client-details')">Cancel</button>
				</div>
            </form>
        </div>
    </div>
</div>
