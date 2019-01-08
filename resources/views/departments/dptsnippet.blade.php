@foreach($departments as $department)
    <div class="row d-flex justify-content-between m-0 bg-light border-bottom w-100">
        <strong class='text-success pl-2'>{{$department['name']}}</strong>
        <div class="d-flex justify-content-between py-1">
            @if ( $department['user'] > 0 )
            <a 
                href="javascript:void(0)"
                onclick="alert('Users assigned to this Department. Delete request denied.')"
                class='badge badge-pill btn btn-secondary mx-2'>Delete</a>
            @else
            <a
                href="javascript:void(0)"
                onclick="deleteDepartment('{{$department['name']}}','{{$department['id']}}')"
                class="badge badge-pill btn btn-outline-danger mx-2">Delete</a>
            @endif
            <a
                href="/departments/{{$department['id']}}/edit"
                class="badge badge-pill btn btn-outline-success mx-2">Edit</a>
        </div>
    </div>
    @if($department['child'] != null)
    <div class="pl-4">
        @include('departments.dptsnippet',['departments'=>$department['child']])
    </div>
    @endif
@endforeach
                            
                            