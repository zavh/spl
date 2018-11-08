<div class="media-body small">
    <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
        <strong class="text-dark pl-1 pt-1">Organization: {{$organization}}</strong>
        <a href="javascript:void(0)" onclick="ajaxFunction('editClient', {{$client_id}} , 'client-details' )" class="pr-2 pt-1">Edit</a>
    </div>
    <p class="text-primary pl-1 mb-0 bg-light ">
        &#9993; {{$address}}
    </p>
</div>