<div class="w-100" id='param_config_{{$level}}'>
    <div class="form-group row mb-1">
        <div class="input-group input-group-sm col-md-12">
            <button 
                class="btn btn-outline-secondary btn-sm badge badge-pill btn-block"
                type="button"
                onclick="addParamFields(this)"
                data-level="{{$level}}"
                data-index=0
                >Add Checkbox Group
            </button>
        </div>
    </div>
    @for($i = 0; $i < count($params); $i++)
    <div class="form-group row mb-1" id='p_param_{{$level}}_{{$i}}'>
        <div class="input-group input-group-sm col-md-12">
            <div class="input-group-prepend">
                <span class="input-group-text" style="width:120px">Parameter Name</span>
            </div>
            <input 
                type="text"
                name='p_param_{{$level}}_input' 
                id='p_param_{{$level}}_{{$i}}_input' 
                class="cpinput form-control"
                data-level="{{$level}}"
                data-index="{{$i}}"
                value="{{$params[$i]['title']}}"
                >
            <div class="input-group-sm">
                <select name="p_param_{{$level}}_{{$i}}_type" id="p_param_{{$level}}_{{$i}}_type" class="form-control" onchange="deleteParam(this)" data-level={{$level}} data-index={{$i}}>
                    <option value="text" {{ $params[$i]['type'] =='text'?'selected':'' }} >Text</option>
                    <option value="number" {{ $params[$i]['type']=='number'?'selected':'' }}>Number</option>
                    <option value="date" {{ $params[$i]['type']=='date'?'selected':'' }}>Date</option>
                    <option value="hidden" {{ $params[$i]['type']=='hidden'?'selected':'' }}>Hidden</option>
                    <option value="delete">Delete This</option>
                </select>
            </div>
        </div>
    </div>
    @endfor
</div>
<div class="w-100">
<div class="form-group row mb-1">
    <div class="input-group input-group-sm col-md-12">
        <button 
            class="btn btn-outline-primary btn-sm btn-block"
            type="button"
            onclick="registerParamFields(this)"
            data-level="{{$level}}"
            data-index="{{$index}}"
            >Submit Parameters
        </button>
{{--         @isset($params)
        <pre>@php print_r($params)@endphp</pre>
        @endisset --}}
    </div>
</div>
</div>