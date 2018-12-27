<div class="form-group row mb-1">
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="width:150px">Add a new Category</span>
        </div>
        <input 
            type="text"
            name='p_cat_{{$level}}_input' 
            id='p_cat_{{$level}}_input' 
            class="cpinput form-control"
            data-level="{{$level}}"
            >
        <div class="input-group-append">
            <button 
                class="btn btn-outline-secondary"
                type="button"
                onclick="initAddCat(this)"
                data-level="{{$level}}"
                data-index=0
                >Add
            </button>
        </div>
    </div>
</div>

<!-- Existing Categories Select Options Start-->
<div class="form-group row mb-0">
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="width:150px">Existing Categories</span>
        </div>
        <select 
            id="p_cat_{{$level}}_list"
            class="cpinput form-control"
            name="p_cat_{{$level}}_list"
            data-level="{{$level}}"
            data-index="{{$index}}"
            onchange="showCat(this)"
            required>
            <option value="-1" disabled selected>Select One</option>
            @isset($subcat)
                @for($i = 0; $i < count($subcat); $i++)
                <option value="{{$i}}">{{$subcat[$i]['name']}}</option>
                @endfor
            @endisset
        </select>
        <span class="invalid-feedback" role="alert" id="project_name_error_span">
            <strong id="project_name_error">{{ $errors->first('project_name') }}</strong>
        </span>
    </div>
</div>
<!-- Existing Categories Select Options Ends-->
<div class="row ml-1" id='category_{{$level}}'>
</div>