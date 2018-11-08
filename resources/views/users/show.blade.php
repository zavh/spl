<div class=" mb-0 bg-white rounded">
	<div class="media text-muted">
		<div class="media-body small">
			<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
				<strong class="text-dark pl-1 pt-1">
				@if($user->id == Auth::User()->id)
					My Profile
				@else 
					Profile of "{{$user->name}}"
				@endif
				</strong>
				<a href="{{route('users.edit', [$user->id])}}" class="pr-2 pt-1">Edit</a>
			</div>
			<div class="row m-0 bg-light border-bottom w-100">
				<div class="col-5 text-right text-primary  text-primary ">Username</div>
				<div class="col-7 text-left text-success pl-0 text-success ">{{$user->name}}</div>
			</div>
			<div class="row m-0 bg-light text-primary border-bottom w-100">
				<div class="col-5 text-right text-primary ">Email</div>
				<div class="col-7 text-left text-success pl-0">{{$user->email}}</div>
			</div>
			<div class="row m-0 bg-light text-primary border-bottom w-100">
				<div class="col-5 text-right text-primary ">First Name</div>
				<div class="col-7 text-left text-success pl-0">{{$user->fname}}</div>
			</div>
			<div class="row m-0 bg-light text-primary border-bottom w-100">
				<div class="col-5 text-right text-primary ">Last Name</div>
				<div class="col-7 text-left text-success pl-0">{{$user->sname}}</div>
			</div>
			<div class="row m-0 bg-light text-primary border-bottom w-100">
				<div class="col-5 text-right text-primary ">Phone</div>
				<div class="col-7 text-left text-success pl-0">{{$user->phone}}</div>
			</div>
			<div class="row m-0 bg-light text-primary border-bottom w-100">
				<div class="col-5 text-right text-primary ">Designation</div>
				<div class="col-7 text-left text-success pl-0">{{$user->designation}}</div>
			</div>
			<div class="row m-0 bg-light text-primary border-bottom w-100">
				<div class="col-5 text-right text-primary ">Department</div>
				<div class="col-7 text-left text-success pl-0">{{$user->department}}</div>
			</div>	
			<div class="row m-0 bg-light text-primary border-bottom w-100">
				<div class="col-5 text-right text-primary ">Address</div>
				<div class="col-7 text-left text-success pl-0">{{$user->address}}</div>
			</div>
			<div class="row m-0 bg-light text-primary border-bottom w-100">
				<div class="col-5 text-right text-primary ">Active from</div>
				<div class="col-7 text-left text-success pl-0">{{$user->created_at}}</div>
			</div>
			<div class="row m-0 bg-light text-primary border-bottom w-100">
				<div class="col-5 text-right text-primary ">Last activity</div>
				<div class="col-7 text-left text-success pl-0">{{$user->updated_at}}</div>
			</div>
				<div class="row m-0 bg-light text-primary w-100">
				<div class="col-5 text-right text-primary ">Role</div>
				<div class="col-7 text-left text-success pl-0">{{$user->role_name}}</div>
			</div>
		</div>
	</div>
</div>
<div class="card-footer bg-transparent mt-0 mb-0 pt-0 pb-0 small">Profile completion: </div>

