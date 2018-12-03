@foreach($clients as $index=>$client)
    <div class="align-items-center p-1 my-1 text-black-50 @if($target==$client->id) bg-success @else bg-light @endif rounded border-bottom " id="parent-of-{{$client->id}}">
        <div class='row'>
            <div class="col-md-6 col-lg-9 col-sm-12">
                <a 
                    href="javascript:void(0)"
                    class="badge badge-light shadow-sm" 
                    onclick="ajaxFunction('viewclient', '{{$client->id}}' , 'client-container')">
                    <span id="client-list-{{$client->id}}">{{$client->organization}}</span>
                        <span class="badge badge-pill badge-warning" title="Number of Projects">
                            <small>
                                {{count($client->projects)}}
                            </small>
                        </span>
                </a>
            </div>
            <div class="col-md-12 col-lg-3 col-sm-12">
            <small>
                @if(count($client->projects) == 0)
                    <a 
                        href="javascript:void(0)" 
                        class='text-danger' 
                        onclick="deleteClient('{{$client->organization}}','{{$client->id}}')">Delete</a>
                @else 
                    <a 
                        href="javascript:void(0)" 
                        title="Projects assigned to the client. Cannot delete."
                        onclick= "alert('Projects assigned to the client. Cannot delete.')"
                        class="text-black-50"
                        style='text-decoration:none'> Delete</a>
                @endif
            </small>
            </div>
        </div>
    </div>
@endforeach