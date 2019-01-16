@if(session()->has('success'))
    <div class='d-flex jusify-conent-between alert alert-dismissable alert-success small'>
        <button type='button' class='close' data-dismiss='alert' aria-label="Close">
            <span aria-hidden="true" style='font-size:10px'>{!! session()->get('success') !!}</span>
        </button>
    </div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block small">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
	<strong>{{ $message }}</strong>
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
	<strong>{{ $message }}</strong>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">×</button>	
	Please check the form below for errors
</div>
@endif