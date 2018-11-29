@foreach ($tasks as $task)
<div class="media text-muted">
    <div class="media-body m-0 small lh-125 border-bottom border-gray">
        <div class="d-flex px-1 py-1 justify-content-between align-items-center w-100 border-bottom">
            <span class="text-gray-dark">{{$task->task_name}}</span>
            <span>
                <a 
                    href="javascript:void(0)" 
                    onclick="ajaxFunction('editTasks', '{{ $task->id }}' , 'taskdiv')"
                    class='badge bg-light shadow-sm'
                    >
                    Edit
                </a>
                    &nbsp;&nbsp;
                    <a href="javascript:void(0)" 
                    onclick="deleteTask('{{$task->task_name}}','{{$task->id}}')"
                    class='badge bg-light shadow-sm'
                    >
                    Del
                </a>
            </span>
        </div>
        <span class="text-danger">Deadline: {{$task->task_deadline}}</span>  
        <span class="text-primary"> Weight: {{$task->weight}}</span>
    </div>
</div>
@endforeach