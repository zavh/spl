@extends('layouts.app')
@section('content')
{{$department->name}}
{{count($product)}}
<div class='container-fluid'>
    <div id='product_design'>
        <input type="text" name='p_cat' id='new_cat'>
        <a href="javascript:void(0)" onclick="addProduct()">Add a product category</a>
    </div>
    <div>
    <select name="p_cat_list" id="p_cat_list"></select>
    </div>
</div>
<div id='subcat'></div>
@endsection
<script src="{{ asset('js/product.js') }}" defer></script>