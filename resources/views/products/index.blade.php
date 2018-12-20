@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class='row'>
        <!-- Left Side Starts-->
        <div class='col-md-4 border p-2 m-2' id='product_design'>
            <!-- New Category Input Starts -->
            @include('products.addcatform',['level'=>1,'index'=>0])
            <!-- New Category Input Ends -->
        </div>
        <!-- Left Side Ends-->
        <div class='col-md-8' id='preview'>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/product.js') }}" defer></script>

                    
                    