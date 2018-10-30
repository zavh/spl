<h6 class="border-bottom border-gray pb-2 mb-0">{{ __('Create new Task') }}</h6>

<form method="POST" action="{{ route('tasks.store') }}" class='mt-3'>
    @csrf
    <!-- Task Name Input Starts-->
    <div class="form-group row">
        <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
            <div class="input-group-prepend">
                <span class="input-group-text">Task Name</span>
            </div>
            <input id="task_name" type="text" class="form-control{{ $errors->has('task_name') ? ' is-invalid' : '' }}" name="task_name" value="{{ old('task_name') }}" required autofocus>

            @if ($errors->has('task_name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('task_name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <!-- Task Name Input Ends-->
    <!-- Task Description Input Starts-->
    <div class="form-group row">
        <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
            <div class="input-group-prepend">
                <span class="input-group-text">Task Description</span>
            </div>
            <input id="task_description" type="text" class="form-control{{ $errors->has('task_description') ? ' is-invalid' : '' }}" name="task_description" value="{{ old('task_description') }}" required>

            @if ($errors->has('task_description'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('task_description') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <!-- Task Description Input Ends-->
    <!-- Assigned Date Input Starts-->
    <div class="form-group row">
        <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
            <div class="input-group-prepend">
                <span class="input-group-text">Assigned Date</span>
            </div>
            <input id="task_date_assigned" type="date" class="form-control{{ $errors->has('task_date_assigned') ? ' is-invalid' : '' }}" name="task_date_assigned" required>

            @if ($errors->has('task_date_assigned'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('task_date_assigned') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <!-- Assigned Date Input Ends-->
    <!-- Deadline Date Input Ends-->
    <div class="form-group row">
        <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
            <div class="input-group-prepend">
                <span class="input-group-text">Deadline</span>
            </div>
            <input id="task_deadline" type="date" class="form-control{{ $errors->has('task_deadline') ? ' is-invalid' : '' }}" name="task_deadline" required>

            @if ($errors->has('task_deadline'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('task_deadline') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <!-- Deadline Date Input Ends-->
    <!-- Deadline Date Input Ends-->
    <div class="form-group row">
        <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
            <div class="input-group-prepend">
                <span class="input-group-text">Task Weight</span>
            </div>
            <input id="weight" type="number" class="form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}" name="weight" max="100" required>
            <div class="input-group-append">
                <span class="input-group-text">%</span>
            </div>
            @if ($errors->has('weight'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('weight') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <!-- Deadline Date Input Ends-->
    <!-- Assign User Input Starts-->
    <div class="input-group" style='margin-top:-10px'>
        <div class="input-group-prepend">
            <span class="input-group-text" style='font-size:12px'>Assign User/s</span>
        </div>
        <select name="user_id[]" id="user_id" class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" required multiple="multiple">
            <option disabled selected>Select One
            @foreach($users as $user)
                @if($user->name != 'admin')
                    <option value="{{$user->id}}">{{$user->name}}</option>
                @endif
            @endforeach
            </select>

            @if ($errors->has('user_id'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('user_id') }}</strong>
                </span>
            @endif                                

    </div>
    <!-- Assign User Input Ends-->
    <div style='margin-top:10px' class="d-flex justify-content-center">
        <input type="hidden" name="project_id" value="{{$project_id}}">
        <!-- <button type="submit" class="btn btn-outline-primary btn-sm btn-block">{{ __('Create Task') }}</button> -->
        <button 
            type="submit" 
            class="btn btn-sm btn-outline-primary mr-2"
            >Create</button>
        <button 
            type="button" 
            class="btn btn-sm btn-outline-secondary ml-2" 
            onclick="ajaxFunction('showTasks', '{{ $project_id }}' , 'taskdiv')"
            >Cancel</button>
    </div>
</form>
