<div class="w-100" id='param_config_{{$level}}'>
    <div class="form-group row mb-1">
        <div class="input-group input-group-sm col-md-12">
            <button 
                class="btn btn-outline-secondary btn-sm badge badge-pill btn-block"
                type="button"
                onclick="addCheckGroup(this)"
                data-level="{{$level}}"
                data-index="{{count($checks) > 0 ? count($checks) : 0}}"
                >Add Checkbox Group
            </button>
        </div>
    </div>

</div>
<div class="w-100">
<div class="form-group row mb-1">
    <div class="input-group input-group-sm col-md-12">
        <button 
            class="btn btn-outline-primary btn-sm btn-block"
            type="button"
            onclick="registerParamFields(this)"
            data-level="{{$level}}"
            data-index=""
            >Submit Parameters
        </button>
{{--         @isset($params)
        <pre>@php print_r($params)@endphp</pre>
        @endisset --}}
    </div>
</div>
</div>