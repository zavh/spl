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
								<strong class="text-dark pl-1 pt-1">List of configured Departments</strong>
                                <a href="/departments/create" class="pr-2 pt-1">Create new Department</a>
							</div>
                            <strong class="d-inline-block mb-2 text-primary">List of Departments</strong>                           
                            <div class="row m-0 bg-light border-bottom w-100">
                                @foreach($departments as $department)
                                <div class="col-md-4 text-primary pl-1 text-success"> Department name: <strong>{{$department->name}}</strong> </div>
                                <div class="col-md-4 text-success pl-1 text-success ">
                                    <div class="d-flex justify-content-between pt-1">
                                        @if ( count($department->users) > 0 )
                                            <a href="javascript:void(0)" onclick="alert('Cannot delete department')" class='badge-secondary badge padge-pill'>Delete</a>
                                        @else
                                            <a href="javascript:void(0)" onclick="deleteDepartment('{{$department->name}}','{{$department->id}}')" class='badge-danger badge padge-pill'>Delete</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 text-primary pl-1 text-secondary"> <a href="/departments/{{$department->id}}/edit" class="pr-2 pt-1">Edit</a></div>
                                @endforeach     
                            </div>                                          
                        </div>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('js/departments.js?version=0.1') }}"></script>