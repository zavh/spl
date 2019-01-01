<div class="form-group row m-0 mb-1 w-100" id="{{$type}}_{{$level}}">
    <div class="input-group input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text" style="width:100px">Group Name</span>
        </div>
        <input type="text" name="p_{{$type}}_input" id="p_{{$type}}_input" class="cpinput form-control">
        <div class="input-group-append">
            <button 
                class="btn btn-outline-secondary" 
                onclick="addGroup(this)"
                data-level="{{$level}}"
                data-index="{{count($data)>0?count($data):0}}"
                data-type="{{$type}}"
                >Add
            </button>
        </div>
    </div>
</div>
<div id='group_config' class='w-100'>
    @for($i = 0; $i < count($data); $i++)
    <div id="{{$type}}_{{$level}}_{{$i}}" name={{$type}}_{{$level}}>
    @isset($els[$i])
        @include("products.configgroup",['name'=>$data[$i]['name'], 'type'=>$type, 'level'=>$level, 'index'=>$i, 'data'=>$els[$i]])
    @endisset
    </div>
    @endfor
    <div id="{{$type}}_{{$level}}_{{count($data)}}" name={{$type}}_{{$level}}>
    </div>
</div>
<div class="w-100">
    <div class="form-group row mb-1">
        <div class="input-group input-group-sm col-md-12">
            <button 
                class="btn btn-outline-primary btn-sm btn-block"
                type="button"
                onclick="registerGroup(this)"
                data-level="{{$level}}"
                data-type="{{$type}}"
                >Submit Parameters
            </button>
        </div>
    </div>
</div>