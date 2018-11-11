<style>
.updateflash{
    -webkit-animation-name: example; /* Safari 4.0 - 8.0 */
    -webkit-animation-duration: 3s; /* Safari 4.0 - 8.0 */
    animation-name: example;
    animation-duration: 3s;
}

/* Safari 4.0 - 8.0 */
@-webkit-keyframes example {
    0%   {background-color:rgb(155, 233, 152);}
    100% {background-color: rgba(0,0,0,0.02);}
}

/* Standard syntax */
@keyframes example {
    0%   {background-color:rgb(155, 233, 152);}
    100% {background-color: rgba(0,0,0,0.02);}
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
                    <div class="card-body">
					<!-- Search Client Section starts -->
					    <div class="align-items-center text-black-50">
                            <div class='row'>
                                <div class="col-md-12">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Search Client" aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-sm" type="button" id="button-addon2">Go</button>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
					<!-- Search Client Section Ends -->
					<!-- Client Listing Section starts -->
						@foreach($assignments as $assignment)
							<div class="align-items-center p-1 my-1 text-black-50 bg-light rounded border-bottom " id="parent-of-{{$assignment->id}}">
								<div class='row'>
									<div class="col-md-6 col-lg-9 col-sm-12">
										<a 
											href="javascript:void(0)"
											class="badge badge-light shadow-sm" 
											onclick="ajaxFunction('viewclient', '{{$assignment->id}}' , 'client-container')">
											<span id="client-list-{{$assignment->id}}">{{$assignment->organization}}</span>
												<span class="badge badge-pill badge-warning" title="Number of Projects">
													<small>
														{{count($assignment->projects)}}
													</small>
												</span>
										</a>
									</div>
									<div class="col-md-12 col-lg-3 col-sm-12">
									<small>
										@if(count($assignment->projects) == 0)
											<a 
												href="javascript:void(0)" 
												class='text-danger' 
												onclick="deleteClient('{{$assignment->name}}','{{$assignment->id}}')">Delete</a>
										@else 
											<a 
												href="javascript:void(0)" 
												title="Projects assigned to the client. Cannot delete."
												class="text-black-50"
												style='text-decoration:none'> Delete</a>
										@endif
									</small>
									</div>
								</div>
							</div>
						@endforeach
					<!-- Client Listing Section ends -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8" id='client-container'>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/clients.js?version=0.6') }}"></script>