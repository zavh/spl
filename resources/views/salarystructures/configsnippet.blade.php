<div class="input-group input-group-sm col-md-12"  style='margin-top:-10px' >
    <input id="{{$data['param_name']}}" type="text" class="form-control" value="{{$data['param_uf_name']}}">
    <select 
        name="param_control_{{$index}}"
        id="param_control_{{$index}}"
        class="form-control" 
        data-index="{{$index}}"
        data-paramname="{{$data['param_name']}}"
        data-paramufname="{{$data['param_uf_name']}}"
        onchange="paramAction(this)">
        <option value="" selected disabled>Action</option>
        <option value="rename">Change Name</option>
        <option value="delete">Delete Parameter</option>
    </select>
</div>