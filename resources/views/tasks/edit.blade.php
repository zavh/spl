<div class='shadow-sm border rounded bg-light mb-0'>
    <div class="border-bottom border-gray">
        <span class='mx-2'>{{ __('Edit Task') }}</span>
    </div>
    <div class='m-2'>
        <form method="POST" action="{{ route('tasks.update', [$task->id]) }}" style='font-size:10px' name='edittask' id='edittask' onsubmit='editTask(event, this)'>
            <input type="hidden" name="allocation" value="{{ $allocation }}" class="etinput">
            <input type="hidden" name="task_id" id="task_id" value="{{ $task->id }}" class="etinput">
            <input type="hidden" name="project_id" value="{{ $task->project_id }}" class="etinput">
            <input type="hidden" name="old_weight" value="{{ $task->weight }}" class="etinput">
        
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style='width:110px'>Task Name</span>
                    </div>
                    <input id="task_name" type="text" class=" etinput form-control" name="task_name" value="{{ $task->task_name }}" required autofocus>
                    <span class="invalid-feedback" role="alert" id="task_name_error_span">
                            <strong id="task_name_error"></strong>
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                    <div class="input-group-prepend">
                        <span class="input-group-text" style='width:110px'>Description</span>
                    </div>
                    <input id="task_description" type="text" class="etinput form-control" name="task_description" value="{{ $task->task_description }}" required>

                    <span class="invalid-feedback" role="alert" id="task_description_error_span">
                            <strong id="task_description_error"></strong>
                    </span>                               
                </div>
            </div>
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                    <div class="input-group-prepend">
                        <span class="input-group-text" style='width:110px'>Deadline</span>
                    </div>
                    <input id="task_deadline" type="date" class="etinput form-control" name="task_deadline" value="{{ $task->task_deadline }}" required>

                    <span class="invalid-feedback" role="alert" id="task_deadline_error_span">
                            <strong id="task_deadline_error"></strong>
                    </span>                                
                </div>
            </div>
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                    <div class="input-group-prepend">
                        <span class="input-group-text" style='width:110px'>Task Weight</span>
                    </div>
                    <input id="weight" type="number" class="etinput form-control" name="weight" value="{{ $task->weight }}" max="100" required>
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                    <span class="invalid-feedback" role="alert" id="weight_error_span">
                            <strong id="weight_error"></strong>
                    </span>
                </div>
            </div>
                <div class="input-group"  style='margin-top:-10px'>
                    <div class="input-group-prepend">
                        <span class="input-group-text" style='width:110px'>Users</span>
                    </div>
                    <select name="user_id" id="user_id" class="etinput form-control" value="{{ $task->user_id }}" required multiple="multiple">
                    <option disabled value=''>Select One</option>
                    @foreach($users as $user)
                        <option value="{{$user['detail']->id}}" {{ $user['selected'] }}>{{$user['detail']->fname}} {{$user['detail']->sname}} - [{{$user['detail']->name}}]</option>
                    @endforeach
                    </select>

                    <span class="invalid-feedback" role="alert" id="user_id_error_span">
                            <strong id="user_id_error"></strong>
                    </span>                                
                </div>
            
            <div style='margin-top:10px' class="d-flex justify-content-center">
            <button 
                type="submit" 
                class="btn btn-sm btn-outline-primary mr-2"
                >Edit</button>
            <button 
                type="button" 
                class="btn btn-sm btn-outline-secondary ml-2" 
                onclick="ajaxFunction('showTasks', '{{ $task->project_id }}' , 'taskdiv')"
                >Cancel</button>
            </div>
        </form>
    </div>
</div>
</div>