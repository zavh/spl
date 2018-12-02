@extends('layouts.app')

@section('content')
<div class="container">
<div class="row justify-content-center" >
		<div class="col-md-8 col-lg-8">
			<div class="card mb-4 shadow-sm h-md-250">
				<div class=" mb-0 bg-white rounded">
					<div class="media text-muted">
						<div class="media-body small mb-2">
							<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
								<span class="text-dark pl-1 pt-1">
									<strong>Changing Details of User</strong>
								</span>
							</div>
							<div class='m-2'>
							<form method='post' action="{{route('users.update', [$user->id])}}" style='width:100%'>
								{{csrf_field()}}
								<input type="hidden" name="_method" value="put">
							<!-- Username Input starts -->
								<div class="form-group row">
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">Username</span>
										</div>
										<input 
											type='text' 
											name='name' 
											value="{{$user->name}}" 
											class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
											name="name" 
											value="{{ $user->name }}"
										>
										@if ($errors->has('name'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('name') }}</strong>
										</span>
										@endif
									</div>
								</div>
							<!-- Username Input ends -->
							<!-- Email Input starts -->
							<div class="form-group row" style='margin-top:-10px'>
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">Email</span>
										</div>
										<input 
											type='text' 
											name='email' 
											value="{{$user->email}}" 
											class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
											name="email" 
											value="{{ $user->email }}"
										>										
										@if ($errors->has('email'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
										@endif
									</div>
								</div>
							<!-- Email Input ends -->
							<!-- First Name Input starts -->
							<div class="form-group row" style='margin-top:-10px'>
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">First Name</span>
										</div>
										<input 
											type='text' 
											name='fname' 
											value="{{$user->fname}}" 
											class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" 
											name="fname" 
											value="{{ $user->fname }}"
										>
										@if ($errors->has('fname'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('fname') }}</strong>
										</span>
										@endif
									</div>
								</div>
							<!-- First Name Input ends -->
							<!-- Last Name Input starts -->
							<div class="form-group row" style='margin-top:-10px'>
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">Last Name</span>
										</div>
										<input 
											type='text'
											name='sname'
											value="{{$user->sname}}"
											class="form-control{{ $errors->has('sname') ? ' is-invalid' : '' }}"
											name="sname"
											value="{{ $user->sname }}"
											>
										@if ($errors->has('sname'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('sname') }}</strong>
										</span>
										@endif
									</div>
								</div>
							<!-- Last Name Input ends -->
							<!-- Designation Input starts -->
							<div class="form-group row" style='margin-top:-10px'>
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">Designation</span>
										</div>
										<select class="form-control" name="designation" required>
											@if($user->designation_id == NULL)
												<option disabled value='' selected>Select One</option>
											@else 
												<option disabled value=''>Select One</option>
											@endif
												@foreach($designations as $designation)
													@if($designation->id == $user->designation_id)
													<option value="{{$designation->id}}" selected>{{$designation->name}}</option>
													@else
													<option value="{{$designation->id}}">{{$designation->name}}</option>
													@endif
												@endforeach
										</select>
									</div>
								</div>
							<!-- Designation Input ends -->
							<!-- Department Input starts -->
								<div class="form-group row" style='margin-top:-10px'>
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">Department</span>
										</div>
										<select class="form-control" name="department" required>
											@if($user->department_id == NULL)
											<option disabled value='' selected>Select One</option>
											@else 
											<option disabled value=''>Select One</option>
											@endif
											@foreach($departments as $department)
												@if($department->id == $user->department_id)
												<option value="{{$department->id}}" selected>{{$department->name}}</option>
												@else
												<option value="{{$department->id}}">{{$department->name}}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>
							<!-- Department Input ends -->
							<!-- Phone Input starts -->
								<div class="form-group row" style='margin-top:-10px'>
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">Phone</span>
										</div>
										<input 
											type='number'
											name='phone'
											value="{{$user->phone}}"
											class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
											name="phone"
											value="{{ $user->phone }}"
										>
										@if ($errors->has('phone'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('phone') }}</strong>
										</span>
										@endif
									</div>
								</div>
							<!-- Phone Input ends -->
							<!-- Address Input starts -->
								<div class="form-group row" style='margin-top:-10px'>
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">Address</span>
										</div>
										<input 
											type='text'
											name='address'
											value="{{$user->address}}"
											class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
											name="address"
											value="{{ $user->address }}"
										>
										@if ($errors->has('address'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('address') }}</strong>
										</span>
										@endif
									</div>
								</div>
							<!-- Address Input ends -->
							@if(Auth::user()->role_id == 1)
							<!-- Role Input starts -->
							<div class="form-group row" style='margin-top:-10px'>
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">Role</span>
										</div>
											<select class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}" name="role_id" value="{{ $user->role_id }}">
												@foreach($roles as $role)
													@if($role->id == $user->role_id)
														<option value="{{$role->id}}" selected>{{$role->role_name}}</option>
													@else 
														<option value="{{$role->id}}">{{$role->role_name}}</option>
													@endif
												@endforeach
											</select>
									</div>
								</div>
							<!-- Role Input ends -->
							<!-- Deactivation starts -->
								<div class="form-group row" style='margin-top:-10px'>
									<div class="input-group input-group-sm col-md-12">
										<div class="input-group-prepend">
											<span class="input-group-text" style="font-size:12px;width:150px">Status</span>
										</div>
											<select class="form-control" name="active">
												<option value=1 
												@if ($user->active==1)
													selected
												@endif>Active</option>
												<option value=0 
												@if ($user->active==0)
														selected
												@endif>Deactivated</option>
											</select>
									</div>
								</div>
								<!-- Deactivation ends -->
								@endif
								<div class='row'>
									<div class='col-6 m-0 pr-1'> 
									<input type="submit" class="btn btn-outline-primary btn-sm btn-block" value="Submit Changes">
									</div>
									<div class='col-6 pl-1'> 
								@if(Auth::User()->role_id == 1)
									<a href="/users" class="btn btn-outline-dark btn-sm btn-block">Cancel</a>
								@else 
									<a href="/home" class="btn btn-outline-dark btn-sm btn-block">Cancel</a>
								@endif
									</div>
								</div>
							</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection