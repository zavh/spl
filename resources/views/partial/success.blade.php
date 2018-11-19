@if(session()->has('success'))
    <div class='d-flex jusify-conent-between alert alert-dismissable alert-success small'>
        <button type='button' class='close' data-dismiss='alert' aria-label="Close">
            <span aria-hidden="true" style='font-size:10px'>{!! session()->get('success') !!}</span>
        </button>
    </div>
@endif