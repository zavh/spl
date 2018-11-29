{{-- <small class="d-block text-right">
@isset($project)
    <a href="javascript:void(0)" onclick="ajaxFunction('showAddTask', '{{ $project->id }}' , 'taskdiv')">
@else 
    <a href="javascript:void(0)" onclick="ajaxFunction('showAddTask', '{{ $project_id }}' , 'taskdiv')">
@endisset
        Add Task
    </a>
</small> --}}
<h6 class="border-bottom border-gray pb-2 mb-0"></h6>
@foreach ($tasks as $task)
<div class="media text-muted pt-3">
    <img data-src="holder.js/32x32?theme=thumb&amp;bg=e83e8c&amp;fg=e83e8c&amp;size=1" alt="32x32" class="mr-2 rounded" style="width: 32px; height: 32px;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2232%22%20height%3D%2232%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_166b8686485%20text%20%7B%20fill%3A%23e83e8c%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A2pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_166b8686485%22%3E%3Crect%20width%3D%2232%22%20height%3D%2232%22%20fill%3D%22%23e83e8c%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2212.166666746139526%22%20y%3D%2216.9%22%3E32x32%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
    <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
        <div class="d-flex justify-content-between align-items-center w-100">
            <strong class="text-gray-dark">{{$task->task_name}} => {{$task->task_description}} </strong>
            <div>
                <a 
                    href="javascript:void(0)" 
                    onclick="ajaxFunction('editTasks', '{{ $task->id }}' , 'taskdiv')">
                    Edit
                </a>
                    &nbsp;&nbsp;
                    <a href="javascript:void(0)" 
                    onclick="deleteTask('{{$task->task_name}}','{{$task->id}}')">
                    Delete
                </a>
                <form 
                    id="task-delete-form-{{$task->id}}"
                    method="post"
                    action="{{route('tasks.destroy', [$task->id])}}" 
                    >
                    <input type="hidden" name="_method" value="delete">
                    {{csrf_field()}}
                </form>
            </div>
        </div>
        <span class="text-danger">Deadline: {{$task->task_deadline}}</span>  
        <span class="text-primary"> Weight: {{$task->weight}}</span>
    </div>
</div>
@endforeach