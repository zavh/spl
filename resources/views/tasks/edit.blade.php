<h6 class="border-bottom border-gray pb-2 mb-0">{{ __('Create new Task') }}</h6>
    <form method="POST" action="{{ route('tasks.update', [$task->id]) }}" style='font-size:10px' name='edittask' id='edittask' onsubmit='editTask(event, this)'>
        {{-- @csrf   --}}
        <input type="hidden" name="allocation" value="{{ $allocation }}">  
        <input type="hidden" name="task_id" id="task_id" class="etinput" value="{{ $task->id }}">
        <input type="hidden" name="project_id" class="etinput" value="{{ $task->project_id }}">    
        {{-- <input name="_method" type="hidden" value="PUT">    --}}
        <div class="form-group row">
            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                <div class="input-group-prepend">
                    <span class="input-group-text">Task Name</span>
                </div>
                <input id="task_name" type="text" class=" etinput form-control{{ $errors->has('task_name') ? ' is-invalid' : '' }}" name="task_name" value="{{ $task->task_name }}" required autofocus>

                <span class="invalid-feedback" role="alert" id="task_name_error_span">
                        <strong id="task_name_error">{{ $errors->first('task_name') }}</strong>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                <div class="input-group-prepend">
                    <span class="input-group-text">Task Description</span>
                </div>
                <input id="task_description" type="text" class="etinput form-control{{ $errors->has('task_description') ? ' is-invalid' : '' }}" name="task_description" value="{{ $task->task_description }}" required>

                <span class="invalid-feedback" role="alert" id="task_description_error_span">
                        <strong id="task_description_error">{{ $errors->first('task_description') }}</strong>
                </span>                               
            </div>
        </div>
        <div class="form-group row">
            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                <div class="input-group-prepend">
                    <span class="input-group-text">Task Date Assigned</span>
                </div>
                <input id="task_date_assigned" type="date" class="etinput form-control{{ $errors->has('task_date_assigned') ? ' is-invalid' : '' }}" name="task_date_assigned" value="{{ $task->task_date_assigned }}" required>

                <span class="invalid-feedback" role="alert" id="task_date_assigned_error_span">
                        <strong id="task_date_assigned_error">{{ $errors->first('task_date_assigned') }}</strong>
                </span>                               
            </div>
        </div>
        <div class="form-group row">
            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                <div class="input-group-prepend">
                    <span class="input-group-text">Task Deadline</span>
                </div>
                <input id="task_deadline" type="date" class="etinput form-control{{ $errors->has('task_deadline') ? ' is-invalid' : '' }}" name="task_deadline" value="{{ $task->task_deadline }}" required>

                <span class="invalid-feedback" role="alert" id="task_deadline_error_span">
                        <strong id="task_deadline_error">{{ $errors->first('task_deadline') }}</strong>
                </span>                                
            </div>
        </div>
        <div class="form-group row">
            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                <div class="input-group-prepend">
                    <span class="input-group-text">Assign Users</span>
                </div>
                <select name="user_id" id="user_id" class="etinput form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" value="{{ $task->user_id }}" required multiple="multiple">
                <option disabled>Select One
                @foreach($users as $user)
                    <option value="{{$user['detail']->id}}" {{ $user['selected'] }}>{{$user['detail']->name}}</option>
                @endforeach
                </select>

                <span class="invalid-feedback" role="alert" id="user_id_error_span">
                        <strong id="user_id_error">{{ $errors->first('user_id') }}</strong>
                </span>                                
            </div>
        </div>
        <div class="form-group row">
                <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                    <div class="input-group-prepend">
                        <span class="input-group-text">Task Weight</span>
                    </div>
                    <input id="weight" type="number" class="etinput form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}" name="weight" value="{{ $task->weight }}" max="100" required>
                    <input type="hidden" name="old_weight" value="{{ $task->weight }}">
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                    <span class="invalid-feedback" role="alert" id="weight_error_span">
                            <strong id="weight_error">{{ $errors->first('weight') }}</strong>
                    </span>
                </div>
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