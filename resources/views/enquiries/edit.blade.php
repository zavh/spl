
<div class="border-bottom border-gray mb-3">
    <span class='pl-2'>{{ __('Edit Enquiry') }}</span>
</div>
<div class="m-2">
<form method="POST" action="{{ route('enquiries.update', [$enquiry->id]) }}" style='font-size:10px'>

    @csrf
    <input name="_method" type="hidden" value="PUT">
    <input name="project_id" type="hidden" value="{{$project_id}}">
    <!-- Pump Type Selection Starts -->
    <div class="form-group row">
        <div class="input-group input-group-sm col-md-12" style='margin-top:-10px'>
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-sm">
                    Pump Type
                </span>
            </div>
            <select name="type" 
                    id="type" 
                    class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}"
                    required
                    aria-label="pump type"
                    aria-describedby="inputGroup-sizing-sm"
                    onchange = "showEnqInputs(this)"
                    >
                <option disabled>Select One</option>
                @if($details->type == 'surface')
                    <option value='surface' selected>Surface</option>
                @else 
                    <option value='surface'>Surface</option>
                @endif

                @if($details->type == 'submerse')
                    <option value='submerse' selected>Submersible</option>
                @else 
                    <option value='submerse'>Submersible</option>
                @endif
            </select>
        </div>
    </div>
    <!-- Pump Type Selection Ends -->
    <!-- Surface Pump Type Selection Starts -->
    @if($details->type == 'surface')
    <div class="form-group row surface-row" style='margin-top:-10px;'>
    @else 
    <div class="form-group row surface-row" style='margin-top:-10px;display:none'>
    @endif
        <div class="input-group input-group-sm col-md-6">
            <div class="input-group-prepend">
                <div class="input-group-text" id="inputGroup-sizing-sm">
                    <input 
                        type="radio" 
                        name='surftype' 
                        required 
                        @isset($details->surftype) 
                            @if($details->surftype == "recirculating") 
                                checked
                            @endif
                        @else disabled
                        @endisset 
                        class="surface-row-el"
                        value="recirculating">
                </div>
            </div>
            <input type="text" class="form-control" disabled value='Recirculating Water'>
        </div>
        <div class="input-group input-group-sm col-md-6">
            <div class="input-group-prepend">
                <div class="input-group-text" id="inputGroup-sizing-sm">
                    <input 
                        type="radio" 
                        name='surftype' 
                        required
                        @isset($details->surftype)
                            @if($details->surftype == "lifting") 
                                checked  
                            @endif
                        @else disabled
                        @endisset  
                        class="surface-row-el"
                        value="lifting">
                </div>
            </div>
            <input type="text" class="form-control" disabled value='Lifting'>
        </div>
    </div>
    <!-- Surface Pump Type Selection Ends -->
    <!-- Submersible Pump Type Selection Starts -->
    @if($details->type == 'submerse')
    <div class="form-group row submerse-row" style='margin-top:-10px;'>
    @else 
    <div class="form-group row submerse-row" style='margin-top:-10px;display:none'>
    @endif
        <div class="input-group input-group-sm col-md-6">
            <div class="input-group-prepend">
                <div class="input-group-text" id="inputGroup-sizing-sm">
                    <input 
                        type="radio" 
                        name='subtype' 
                        required
                        @isset($details->subtype) 
                            @if($details->subtype == "borewell") 
                                checked  
                            @endif
                        @else disabled
                        @endisset
                        class="submerse-row-el" 
                        value='borewell'>
                </div>
            </div>
            <input type="text" class="form-control" aria-label="Text input with radio button" disabled value='Bore Well'>
        </div>
        <div class="input-group input-group-sm col-md-6">
            <div class="input-group-prepend">
                <div class="input-group-text" id="inputGroup-sizing-sm">
                    <input 
                        type="radio"
                        name='subtype' 
                        required 
                        @isset($details->subtype)
                            @if($details->subtype == "openwell")
                                checked
                            @endif
                        @else disabled
                        @endisset
                        class="submerse-row-el"
                        value='openwell'>
                </div>
            </div>
            <input type="text" class="form-control" aria-label="Text input with radio button" disabled value='Open Well'>
        </div>
    </div>
    <!-- Submersible Pump Type Selection Ends -->
    <div class="form-group row" style='margin-top:-10px'>
        <!-- Pump Head Input Type starts -->
        <div class="input-group input-group-sm col-md-6">
            <div class="input-group-prepend">
                <span class="input-group-text">Head</span>
            </div>
            <input 
                id="pumphead" 
                type="text" 
                class="form-control{{ $errors->has('pumphead') ? ' is-invalid' : '' }}" 
                name="pumphead" 
                placeholder="In Meter" 
                value="{{ $details->pumphead }}" 
                required>
            @if ($errors->has('pumphead'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('pumphead') }}</strong>
                </span>
            @endif
        </div>
        <!-- Pump Head Input Type ends -->
        <!-- Pump Capacity Input Type starts -->
        <div class="input-group input-group-sm col-md-6">
            <div class="input-group-prepend">
                <span class="input-group-text">Capacity</span>
            </div>
            <input 
                id="pumpcap" 
                type="text" 
                class="form-control{{ $errors->has('pumpcap') ? ' is-invalid' : '' }}" 
                name="pumpcap" 
                placeholder="In Cubic Meter" 
                value="{{ $details->pumpcap }}"
                required>

            @if ($errors->has('pumpcap'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('pumpcap') }}</strong>
                </span>
            @endif
        </div>
        <!-- Pump Capacity Input Type starts -->
    </div>

    <div class="form-group row" style='margin-top:-10px'>
        <div class="input-group input-group-sm col-md-6">
            <div class="input-group-prepend">
                <span class="input-group-text">Liquid Name</span>
            </div>
            <input 
                id="liquid" 
                type="text" 
                class="form-control{{ $errors->has('liquid') ? ' is-invalid' : '' }}" 
                name="liquid" 
                value="{{ $details->liquid }}"
                required>

            @if ($errors->has('liquid'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('liquid') }}</strong>
                </span>
            @endif
        </div>
        <div class="input-group input-group-sm col-md-6">
            <div class="input-group-prepend">
                <span class="input-group-text">Liquid Temperature</span>
            </div>
            <input id="liqtemp" type="text" class="form-control{{ $errors->has('liqtemp') ? ' is-invalid' : '' }}" name="liqtemp" value="{{ $details->liqtemp }}" required>

            @if ($errors->has('liqtemp'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('liqtemp') }}</strong>
                </span>
            @endif
        </div>                            
    </div>

    <div class="input-group" style='margin-top:-10px'>
        <div class="input-group-prepend">
            <span class="input-group-text" style='font-size:12px'>Description</span>
        </div>
        <textarea class="form-control" aria-label="With textarea" name='description' value="" required>{{ $details->description }}</textarea>
    </div>

    <div class="form-group row" style='margin-top:5px'>
        <div class="input-group input-group-sm col-md-6">
            <button type="submit" class="btn btn-outline-primary btn-block btn-sm">
                {{ __('Submit') }}
            </button>
            
        </div>
        <div class="input-group input-group-sm col-md-6">
            <button 
                type="button" 
                class="btn btn-outline-secondary btn-block btn-sm" 
                onclick="ajaxFunction('showEnquiries', '{{$project_id}}' , 'enqdiv')">
                Cancel
            </button>
        </div>
    </div>
        
    
</form>
</div>
