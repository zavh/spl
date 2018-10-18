@if(session()->has('success'))
    <div class='alert alert-dismissable alert-success'>
        <button type='button' class='close' data-dismiss='alert' aria-label="Close" style='position:absolute;top:2px;right:2px;font-size:11px;'>
            <span aria-hidden="true">&#10060;</span>
        </button>
        <strong>
            {!! session()->get('success') !!}
        </strong>
    </div>
@endif