<div class="my-1 bg-light rounded shadow-sm border" >
@foreach ($tasks as $task)
    <div class="media text-muted">
        <div class="media-body m-0 small lh-125 border-bottom border-gray">
            <div class="d-flex px-1 py-1 justify-content-between align-items-center w-100 border-bottom">
                <span class="text-gray-dark">{{$task->task_name}}</span>
                <span>
                    <a 
                        href="javascript:void(0)" 
                        onclick="ajaxFunction('editTasks', '{{ $task->id }}' , 'taskdiv')"
                        class='badge bg-light shadow-sm border'
                        >
                        &#9997;
                    </a>
                        &nbsp;&nbsp;
                        <a href="javascript:void(0)" 
                        onclick="deleteTask('{{$task->task_name}}','{{$task->id}}')"
                        class='badge bg-light shadow-sm border'
                        >
                        &#x2718;
                    </a>
                </span>
            </div>
            <span class="text-danger">Deadline: {{$task->task_deadline}}</span>  
            <span class="text-primary"> Weight: {{$task->weight}}</span>
        </div>
    </div>
@endforeach

<div class="accordion" id="taskAccordion">
@foreach ($tasks as $index=>$task)
    <div class="card">
        <div id="heading{{$index}}" class='border-bottom d-flex px-1 py-1 justify-content-between align-items-center w-100'>
            <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapse{{$index}}" aria-expanded="true" aria-controls="collapseOne">
            {{$task->task_name}}
            </button>
            <span>
                    <a 
                        href="javascript:void(0)" 
                        onclick="ajaxFunction('editTasks', '{{ $task->id }}' , 'taskdiv')"
                        class='badge bg-light shadow-sm border'
                        >
                        &#9997;
                    </a>
                        &nbsp;&nbsp;
                        <a href="javascript:void(0)" 
                        onclick="deleteTask('{{$task->task_name}}','{{$task->id}}')"
                        class='badge bg-light shadow-sm border'
                        >
                        &#x2718;
                    </a>
                </span>
        </div>

        <div id="collapse{{$index}}" class="collapse  @if($index == 0) show @endif" aria-labelledby="heading{{$index}}" data-parent="#taskAccordion">
        <div class="card-body">
            {{$task->task_name}}
        </div>
        </div>
    </div>
@endforeach
</div>
</div>