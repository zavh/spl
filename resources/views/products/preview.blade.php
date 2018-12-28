<div class="row ml-1">
    <div class="row w-100 mx-2 mt-2">
        <div class="shadow-sm w-100 border rounded">
            <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                <span class="pl-2">Product: {{$product['name']}}</span>
            </div>
            <div class="p-1 m-1" id="{{$product['name']}}_{{$product['level']}}_{{$product['level']}}_params">
                @if(count($product['subcategory'])>0)
                    <div class="form-group row mb-1">
                        <div class="input-group input-group-sm col-md-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="width:150px">{{$product['name']}} Subcategory</span>
                            </div>
                            <select name="" id="" class="form-control">
                                <option value="-1" disabled="" selected>Select One</option>
                            @for($i = 0; $i < count($product['subcategory']); $i++)
                                <option value="{{$i}}">{{$product['subcategory'][$i]['name']}}</option>
                            @endfor
                            </select>
                        </div>
                    </div>
                @endif
            </div>
            <div class="p-1 m-1" id="{{$product['name']}}_{{$product['level']}}_{{$product['level']}}_params">
                @if(count($product['params'])>0)
                    @for($i = 0; $i < count($product['params']); $i++)
                        <div class="form-group row mb-1">
                            <div class="input-group input-group-sm col-md-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:100px">{{$product['params'][$i]['title']}}</span>
                                </div>
                                <input type="{{$product['params'][$i]['type']}}" name="p_cat_1_input" id="p_cat_1_input" class="cpinput form-control" data-level="1">
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </div>
</div>
<pre>
@php
print_r($product);
@endphp
</pre>