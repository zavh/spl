@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class='row'>
        <!-- Left Side Starts-->
        <div class='col-md-4' id='product_design'>
            <!-- New Category Input Starts -->
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width:150px">Add a new Category</span>
                    </div>
                    <input type="text" name='p_cat_1_input' id='p_cat_1_input' class="cpinput form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="addCategory(1)">Add</button>
                    </div>
                </div>
            </div>
            <!-- New Category Input Ends -->
            <!-- Existing Categories Select Options Start-->
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width:150px">Existing Categories</span>
                    </div>
                    <select id="p_cat_1_list" class="cpinput form-control" name="p_cat_1_list"  required>
                    </select>
                    <span class="invalid-feedback" role="alert" id="project_name_error_span">
                        <strong id="project_name_error">{{ $errors->first('project_name') }}</strong>
                    </span>
                </div>
            </div>
            <!-- Existing Categories Select Options Ends-->
            <div class="row" id='config'></div>
        </div>
        <!-- Left Side Ends-->
        <div class='col-md-8' id='preview'>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/product.js') }}" defer></script>

                    
                    