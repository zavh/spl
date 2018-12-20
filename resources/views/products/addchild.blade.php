<div class='row w-100 mx-3 mt-2'>
    <div class='shadow-sm w-100 border rounded'>
        <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
            <span class='pl-2'>
            @if($product['level'] == 2)
                            Category: {{$product['name']}}
                        @else Sub Category: {{$product['name']}}
                        @endif
            </span>
            <span class='pr-2'>
                <select 
                    name="options_{{$product['level']}}" 
                    id="options_{{$product['level']}}"
                    onchange='configSubCat(this)'
                    data-level="{{$product['level']}}"
                    >
                    <option disabled selected>Select One</option>
                    <option value="subcategory">Add Subcategory</option>
                    <option value="param">Add Parameter</option>
                    <option value="radios">Add Radio Option</option>
                    <option value="checks">Add Checkbox Option</option>
                    <option value="txtarea">Add Description Box</option>
                </select>
            </span>
        </div>
        <div class="row p-1 m-1" id="config_{{$product['level']}}"></div>  
    </div>
</div>