
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Client</div>
                    <div class="card-body">
                    <form method="POST" action="{{route('clients.update', [$assignment->id])}}" onsubmit="return updateClient(event)">
                        @csrf           
                        <div class="form-group row">
                            <label for="organization" class="col-sm-4 col-form-label text-md-right">{{ __('Organization') }}</label>
                            <div>
                                <input id="organization" type="text" class="form-control{{ $errors->has('organization') ? ' is-invalid' : '' }}" name="organization" value="{{ $assignment->organization }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-4 col-form-label text-md-right">{{ __('Address') }}</label>
                            <div>
                                <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $assignment->address }}" required>
                            </div>
                        </div>
                        <input name="_method" type="hidden" value="PUT">
                        <div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                {{ __('Update Client') }}
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="ajaxFunction('cancelUpdate', '{{$assignment->id}}' , 'client-details')">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                        <input type="hidden" id="client_id" value="{{$assignment->id}}">
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
