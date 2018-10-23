<div class="card shadow-lg">
    <div class="card-header text-white bg-primary">
                    {{ __('Client Details') }}
    </div>
<<<<<<< HEAD
    <div class="card-body">
    Client Contact: : <strong> {{$assignment->name}} </strong>
    </div>
    <div class="card-footer bg-transparent border-primary">Organization: : <strong> {{$assignment->organization}} </strong></div>
    <div class="card-footer bg-transparent border-primary">Address : <strong>{{$assignment->address}}</strong></div>
</div>
=======
    <a href="/clients/{{$assignment->id}}/edit" class="btn btn-primary">Edit</a>
    <a href="/clients" class="btn btn-primary">Go Back</a>

    {{-- {!!Form::open(['action'=>['ClientsController@destroy',$assignment->id],'method'=>'POST','class'=>'pull-right'])!!}
        {{Form::hidden('_method','DELETE')}}
        {{Form::submit('Delete',['class'=>'btn btn-danger'])}}
    {!!Form::close()!!} --}}
    
@endsection
>>>>>>> 8cc324f9e7482648ce736a1dd246b6635502d2dd
