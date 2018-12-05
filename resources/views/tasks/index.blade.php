<div class="my-1 bg-light rounded shadow-sm border" >
<div class="accordion" id="taskAccordion">
@foreach ($tasks as $index=>$task)
    <div class="card small">
        <div id="heading{{$index}}" class='border-bottom d-flex justify-content-between align-items-center w-100'>
            <span>
                <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapse{{$index}}" aria-expanded="true" aria-controls="collapseOne">
                    {{$task->task_name}}
                </button>
            </span>

            <span class='mx-1'>
                <span class='badge badge-light border border shadow-sm mx-2'>Weight {{$task->weight}}</span>
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
                    x
                </a>
            </span>
        </div>

        <div id="collapse{{$index}}" class="collapse " aria-labelledby="heading{{$index}}" data-parent="#taskAccordion">
            <div class='border-bottom text-success'> <p class='px-4 mb-0 pb-0'>{{$task->task_description}}</p></div>
            <div class="text-danger border-bottom"> <span class='px-4'> Deadline: {{$task->task_deadline}}</span></div>
            <div class="text-primary"> 
                <p class='px-4 pb-0 mb-0 text-muted'>Assigned to:
                @foreach ($task->users as $user)
                    <p class='px-5 pb-0 mb-0'>
                            {{$user->fname}} {{$user->sname}}
                    </p>
                    
                @endforeach
                </p>
            </div>
        </div>
    </div>
@endforeach
</div>
</div>