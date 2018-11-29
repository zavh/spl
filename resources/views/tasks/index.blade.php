<div class="my-1 bg-light rounded shadow-sm border" >
<div class="accordion" id="taskAccordion">
@foreach ($tasks as $index=>$task)
    <div class="card small">
        <div id="heading{{$index}}" class='border-bottom d-flex justify-content-between align-items-center w-100'>
            <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapse{{$index}}" aria-expanded="true" aria-controls="collapseOne">
            {{$task->task_name}} <span class='badge badge-warning border border-dark'>{{$task->weight}}</span>
            </button>
            <span class='mx-1'>
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
        <div class="bg-light">
            <div class='border-bottom'> <p class='px-4'>{{$task->task_description}}</p></div>
            <div class="text-danger border-bottom"> <span class='px-4'> Deadline: {{$task->task_deadline}}</span></div>
            <div class="text-primary"> <span class='px-4'>Weight: {{$task->weight}}</span></div>
        </div>
        </div>
    </div>
@endforeach
</div>
</div>