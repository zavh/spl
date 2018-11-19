@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row" >
            <div class="col-md-12 col-lg-8">
                <div class="card mb-4 shadow-sm h-md-250">
				<div class=" mb-0 bg-white rounded">
					<div class="media text-muted">
						<div class="media-body small">
							<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
								<strong class="text-dark pl-1 pt-1">List of configured users</strong>
								<a href="/users/create" class="pr-2 pt-1">Create New User</a>
							</div>
							@foreach($users as $index=>$user)
								<div class="row m-0 bg-light border-bottom w-100">
									<div class="col-md-4 text-primary pl-1 text-secondary">Username: <strong>{{$user->name}}</strong></div>
									<div class="col-md-4 text-success pl-1 text-success ">Email: {{$user->email}}</div>
									<div class="col-md-4 text-success pl-1 text-success ">
										<div class="d-flex justify-content-between pt-1">
											@if ( count($user->reports) > 0 || count($user->tasks)>0 )
												<a href="javascript:void(0)" onclick="alert('Cannot delete user')" class='badge-secondary badge padge-pill'>Delete</a>
											@else
												<a href="javascript:void(0)" onclick="deleteUser('{{$user->name}}','{{$user->id}}')" class='badge-danger badge padge-pill'>Delete</a>
											@endif
										
										<a href="javascript:void(0)" onclick="ajaxFunction('viewuser', '{{$user->id}}', 'user-container')" class='badge-primary badge padge-pill'>Details</a>
										<a href="javascript:void(0)" onclick="deactivateUser('{{$user->name}}','{{$user->id}}')" class='badge-warning badge padge-pill'>Deactivate</a>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
            </div>      
        </div>
        
        <div class="col-md-12 col-lg-4">
          <div class="card mb-4 shadow-sm" id='user-container'>
				@include('users.show', ['user'=>$me])
          </div>
        </div>
      </div>
    </div>
	<form id="user-delete-form-{{$user->id}}" method="post" action="{{route('users.destroy', [$user->id])}}" >
		<input type="hidden" name="_method" value="delete">
		{{csrf_field()}}
	</form>
@endsection
<script src="{{ asset('js/users.js?version=0.1') }}"></script>