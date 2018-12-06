<div class='shadow-sm border rounded bg-light mb-0'>
    <div class="border-bottom border-gray">
        <span class='mx-2'>{{ __('Create new Task') }}</span>
    </div>
    <div class='m-2'>
        <form method="POST" action="{{ route('tasks.store') }}" style='font-size:10px' name='createtask' id='createtask' onsubmit='createTask(event, this)'>
            @csrf
            <input type="hidden" name="allocation"  class="ctinput" value="{{ $allocation }}">
            <input type="hidden" name="project_id" class="ctinput" value="{{$project_id}}">
            <!-- Task Name Input Starts-->
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12"  >
                    <div class="input-group-prepend">
                        <span class="input-group-text" style='width:110px'>Task Name</span>
                    </div>
                    <input id="task_name" type="text" class="ctinput form-control{{ $errors->has('task_name') ? ' is-invalid' : '' }}" name="task_name" value="{{ old('task_name') }}" required autofocus>

                    <span class="invalid-feedback" role="alert" id="task_name_error_span">
                            <strong id="task_name_error"></strong>
                    </span>
                </div>
            </div>
            <!-- Task Name Input Ends-->
            <!-- Task Description Input Starts-->
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                    <div class="input-group-prepend">
                        <span class="input-group-text" style='width:110px'>Task Description</span>
                    </div>
                    <input id="task_description" type="text" class="ctinput form-control{{ $errors->has('task_description') ? ' is-invalid' : '' }}" name="task_description" value="{{ old('task_description') }}" required>

                    <span class="invalid-feedback" role="alert" id="task_description_error_span">
                            <strong id="task_description_error"></strong>
                    </span>
                </div>
            </div>
            <!-- Task Description Input Ends-->
            <!-- Deadline Date Input Ends-->
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                    <div class="input-group-prepend">
                        <span class="input-group-text" style='width:110px'>Deadline</span>
                    </div>
                    <input id="task_deadline" type="date" class="ctinput form-control{{ $errors->has('task_deadline') ? ' is-invalid' : '' }}" name="task_deadline" required>

                    <span class="invalid-feedback" role="alert" id="task_deadline_error_span">
                            <strong id="task_deadline_error"></strong>
                    </span>
                </div>
            </div>
            <!-- Deadline Date Input Ends-->
            <!-- Deadline Date Input Ends-->
            <div class="form-group row">
                <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                    <div class="input-group-prepend">
                        <span class="input-group-text" style='width:110px'>Task Weight</span>
                    </div>
                    <input id="weight" type="number" class="ctinput form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}" name="weight" max="100" required>
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>

                    <span class="invalid-feedback" role="alert" id="weight_error_span">
                            <strong id="weight_error"></strong>
                    </span>
                </div>
            </div>
            <!-- Deadline Date Input Ends-->
            <!-- Assign User Input Starts-->
            <div class="input-group" style='margin-top:-10px'>
                <div class="input-group-prepend">
                    <span class="input-group-text" style='width:110px' style='font-size:12px'>Assign User/s</span>
                </div>
                <select name="user_id" id="user_id" class="ctinput form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" required multiple="multiple">
                    <option disabled selected>Select One
                    @foreach($users as $user)
                        @if($user->name != 'admin')
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endif
                    @endforeach
                    </select>

                    <span class="invalid-feedback" role="alert" id="user_id_error_span">
                            <strong id="user_id_error"></strong>
                    </span>                                

            </div>
            <!-- Assign User Input Ends-->
            <div class="form-group row mt-2 mb-0">
                <div class="input-group input-group-sm col-md-6">
                    <button type="submit" class="btn btn-outline-primary btn-block btn-sm">
                        Create
                    </button>
                    
                </div>
                <div class="input-group input-group-sm col-md-6">
                    <button type="button" class="btn btn-outline-secondary btn-block btn-sm" onclick="ajaxFunction('showTasks', '{{ $project_id }}' , 'taskdiv')">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
