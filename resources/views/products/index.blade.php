@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class='row'>
        <!-- Left Side Starts-->
        <div class='col-md-5' id='product_design'>
            <div class="m-2 p-2 border">
            <!-- New Category Input Starts -->
            @include('products.addcatform',['level'=>1,'index'=>0])
            <!-- New Category Input Ends -->
            </div>
        </div>
        <!-- Left Side Ends-->
        <div class='col-md-7' id='preview'>
            <div class="p-2 m-2 w-100">asdaksjd</div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/product.js') }}" defer></script>

                    
                    