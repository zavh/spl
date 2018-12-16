<div class="form-group row">
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="width:150px">Add a new Category</span>
        </div>
        <input type="text" name='p_cat_{{$level}}_input' id='p_cat_{{$level}}_input' class="cpinput form-control">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="addCategory({{$level}})">Add</button>
        </div>
    </div>
</div>