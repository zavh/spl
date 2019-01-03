<div class="row">
    <div class="card mb-4 shadow-sm w-100 mx-4">
        <div class=" mb-0 bg-white rounded">
            <div class="media text-muted">
                <div class="media-body small">
                    <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                        <strong class="text-dark pl-1 pt-1">Details of "{{$name}}"</strong>
                        <a href="/salarystructures/{{$id}}/edit" class='small mx-2'>Edit</a>
                    </div>
                    @foreach($salarystructure as $field)
                    <div class="row m-0 bg-light border-bottom w-100">
                        <div class="col-5 text-right text-primary  text-primary ">{{$field->param_uf_name}}</div>
                        <div class="col-7 text-left text-success pl-0 text-success ">{{$field->value}}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>