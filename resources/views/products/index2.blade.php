@extends('layouts.app')
@section('content')
<select name="" id="" size=20>
<option value="0">50-32-125</option>
<option value="1">50-32-160</option>
<option value="2">50-32-200</option>
<option value="3">50-32-250</option>
<option value="4">65-40-125</option>
<option value="5">65-40-125</option>
<option value="6">65-40-160</option>
</select>
@endsection
<script>
var products = [
    {"Motor":"2hp","Base Frame and Coupling":'1',"Control Panel":'1',"accessories":['Rubber Dice','Coupling']},
    {"Motor":"3hp","Base Frame and Coupling":'1',"Control Panel":'1',"accessories":['Rubber Dice','Coupling']},
    {"Motor":"4hp","Base Frame and Coupling":'1',"Control Panel":'1',"accessories":['Rubber Dice','Coupling']},
    {"Motor":"5hp","Base Frame and Coupling":'1',"Control Panel":'1',"accessories":['Rubber Dice','Coupling']},
    {"Motor":"6hp","Base Frame and Coupling":'1',"Control Panel":'1',"accessories":['Rubber Dice','Coupling']},
    {"Motor":"7hp","Base Frame and Coupling":'1',"Control Panel":'1',"accessories":['Rubber Dice','Coupling']},
    ];
</script>
<script src="{{ asset('js/product.js') }}" defer></script>

                    
                    