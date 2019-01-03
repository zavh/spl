@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center" >
		<div class="col-md-10 col-lg-12">
			<div class="card mb-4 shadow-sm h-md-250">
				<div class=" mb-0 bg-white rounded">
					<div class="media text-muted">
						<div class="media-body small mb-0">
							<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                                <!-- Header Starts -->
                                <span class="text-dark pl-1 pt-1">
                                    <strong>Edit User</strong>
                                </span>
                            </div>
                                <!-- Header Ends -->
                                <div class="card-body">
                                    <form method="POST" action="{{ route('users.update', [$user->id]) }}" onsubmit="editwithSalary(event, this)" name='editUser' id='editUser'>
                                        @csrf
                                        <input name="_method" type="hidden" value="PUT">
                                        <input type="hidden" name="sal_id" id="sal_id" value="{{ $salary->id }}" class="useraccount">
                                       
                                        <div class='row'>
                                            <div class='col-md-4 m-0'> 
                                                @include("users.edit_account")
                                            </div> 
                                            @if(Auth::User()->role_id == 1)
                                                <div class='col-md-4'> 
                                                    @include("users.edit_salary_1")
                                                </div>
                                                <div class='col-md-4'> 
                                                    @include("users.edit_salary_2")
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class='row'>
                                            <div class='col-6 m-0 pr-1'> 
                                                <input type="submit" class="btn btn-outline-primary btn-sm btn-block" value="Edit">
                                            </div>
                                            <div class='col-6 pl-1'> 
                                                <a href="/users" class="btn btn-outline-dark btn-sm btn-block">Cancel</a>
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
    </div>
</div>
@endsection
<script src="{{ asset('js/users.js') }}"></script>
<script src="{{ asset('js/ajaxformprocess.js') }}"></script>
