@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">
                    Changing Details of Username : <strong>"{{$user->name}}"</strong>
                </div>
                <div class="card-body">
					<form method='post' action="{{route('users.update', [$user->id])}}" style='width:100%'>
							{{csrf_field()}}
							<input type="hidden" name="_method" value="put">
							<table class="table table-hover table-sm">
								<tbody>
									<tr>
										<th scope="col">User Name</th>
										<td> <input type='text' name='name' value="{{$user->name}}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}"> 
									
									{{-- /<tr> --}}
										@if ($errors->has('name'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('name') }}</strong>
										</span>
										@endif
									{{-- </tr>                    --}}
									</td></tr>
									<tr>
										<th scope="col">Email</th>
										<td> <input type='text' name='email' value="{{$user->email}}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}"> 
									{{-- <tr> --}}
										@if ($errors->has('email'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
										@endif
									{{-- </tr>                    --}}
									</td></tr>
									<tr>
										<th scope="col">First Name</th>
										<td> <input type='text' name='fname' value="{{$user->fname}}" class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" name="fname" value="{{ $user->fname }}"> 
									{{-- <tr> --}}
										@if ($errors->has('fname'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('fname') }}</strong>
										</span>
										@endif
									{{-- </tr>                    --}}
									</td></tr>
									<tr>
										<th scope="col">Last Name</th>
										<td> <input type='text' name='sname' value="{{$user->sname}}" class="form-control{{ $errors->has('sname') ? ' is-invalid' : '' }}" name="sname" value="{{ $user->sname }}"> 
									{{-- <tr> --}}
										@if ($errors->has('sname'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('sname') }}</strong>
										</span>
										@endif
									</td></tr>
									<tr>
										<th scope="col">Designation</th>
										<td>
											<select class="form-control" name="designation" required>
												<option disabled value=''>Select One</option>
												@foreach($designations as $designation)
													@if($designation->id == $user->designation_id)
													<option value="{{$designation->id}}" selected>{{$designation->name}}</option>
													@else
													<option value="{{$designation->id}}">{{$designation->name}}</option>
													@endif
												@endforeach
											</select>
										</td>
									</tr>									<tr>
										<th scope="col">Department</th>
										<td>
											<select class="form-control" name="department" required>
												<option disabled value=''>Select One</option>
												@foreach($departments as $department)
													@if($department->id == $user->department_id)
													<option value="{{$department->id}}" selected>{{$department->name}}</option>
													@else
													<option value="{{$department->id}}">{{$department->name}}</option>
													@endif
												@endforeach
											</select>
										</td>
									</tr>
									<tr>
										<th scope="col">Phone</th>
										<td> <input type='number' name='phone' value="{{$user->phone}}" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ $user->phone }}"> 
									{{-- <tr> --}}
										@if ($errors->has('phone'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('phone') }}</strong>
										</span>
										@endif
									{{-- </tr>                    --}}
									</td></tr>
									<tr>
										<th scope="col">Address</th>
										<td> <input type='text' name='address' value="{{$user->address}}" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $user->address }}"> 
									{{-- <tr> --}}
										@if ($errors->has('address'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('address') }}</strong>
										</span>
										@endif
									{{-- </tr>                   --}}
									</td></tr>
									@if(Auth::user()->role_id == 1)
									<tr>
										<th scope="col">Role</th>
											<td>
												<select class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}" name="role_id" value="{{ $user->role_id }}">
													@foreach($roles as $role)
														@if($role->id == $user->role_id)
															<option value="{{$role->id}}" selected>{{$role->role_name}}</option>
														@else 
															<option value="{{$role->id}}">{{$role->role_name}}</option>
														@endif
													@endforeach
												</select>
											
										{{-- <tr> --}}
											@if ($errors->has('role_id'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('role_id') }}</strong>
											</span>
											@endif
										</td>
									</tr>	
									@endif
								</tbody>
							</table>
							<div class='row d-flex justify-content-center'> 
								<input type="submit" class="btn btn-primary btn-sm m-2" value="Submit Changes">
								@if(Auth::User()->role_id == 1)
									<a href="/users" class="btn btn-secondary btn-sm m-2">Cancel</a>
								@else 
									<a href="/home" class="btn btn-secondary btn-sm m-2">Cancel</a>
								@endif
							</div>
					</form>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection