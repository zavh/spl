<style>
.updateflash{
    -webkit-animation-name: example; /* Safari 4.0 - 8.0 */
    -webkit-animation-duration: 3s; /* Safari 4.0 - 8.0 */
    animation-name: example;
    animation-duration: 3s;
}

/* Safari 4.0 - 8.0 */
@-webkit-keyframes example {
	0% {background-color: rgba(0,0,0,0.02);}
    100%   {background-color:rgb(92,184,92);}
}

/* Standard syntax */
@keyframes example {
	0% {background-color: rgba(0,0,0,0.02);}
    100%   {background-color:rgb(92,184,92);}
}
</style>
@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-4">
            <div class="card-deck">
                <div class="card shadow-sm">
                    <div class="border-bottom">
						<div class="media text-muted ml-2 mr-2">
							<div class="media-body pb-0 mb-0 lh-125">
								<div class="d-flex justify-content-between align-items-center w-100">
									<strong class="text-gray-dark">Cleint Listing</strong>
									<a href="/clients/create" class='small'>Create Client</a>
								</div>
							</div>
						</div>
                    </div>
                    <div class="card-body py-2">
					<!-- Search Client Section starts -->
					    <div class="align-items-center text-black-50">
                            <div class='row'>
                                <div class="col-md-12">
								<form action="" class='m-0 p-0' autocomplete="off" id="findclientnames" name='findclientnames' onsubmit='findclientNames(event, this)'>
									<div class="input-group input-group-sm">
										<input type="text" name='clientname' id='clientname' class="cnlinput form-control" placeholder="Search Client" aria-label="Recipient's username" aria-describedby="button-addon2">
										<div class="input-group-append">
											<button class="btn btn-secondary btn-sm" type="submit" id="button-addon2">Go</button>
										</div>
										<div class="autocomplete"></div>
									</div>
								</form>
								<script>
									var x = '{{$names}}' ;
									var names = x.split(',');
									var inp = document.getElementById("clientname");
									autocomplete(document.getElementById("clientname"), names);
								</script>
                            </div>
                            </div>
                        </div>
					<!-- Search Client Section Ends -->
					<!-- Client Listing Section starts -->
					<div id="display-names">
						@include('clients.showclientlist')
					</div>
					<!-- Client Listing Section ends -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8" id='client-container'>
			<script>
				@if($target != null)
					ajaxFunction('viewclient', '{{$target}}' , 'client-container');
				@endif
			</script>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/clients.js?version=0.6') }}"></script>
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>
<script src="{{ asset('js/deleteform.js') }}"></script>
<script src="{{ asset('js/search.js') }}"></script>