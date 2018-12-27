<div class="form-group row my-1" id="p_param_2_1">
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="width:120px">Parameter Name</span>
        </div>
        <input 
            name="p_param_{{$data['type']}}_{{$data['level']}}_{{$data['index']}}_input" 
            type="text" 
            class="cpinput form-control"
            @isset($data['value'])
                value = "{{$data['value']}}"
            @endisset
            >
        <div class="input-group-append">
            <button 
                class="btn btn-outline-secondary" 
                type="button" 
                onclick=""
                data-type="{{$data['type']}}"
                data-level="{{$data['level']}}"
                data-index="{{$data['index']}}"
                data-grpindex="{{$data['grpindex']}}"
                >X
            </button>
        </div>
    </div>
</div>