<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-header text-white bg-primary">
                                {{ __('Edit Task') }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.update', [$task->id]) }}">
                        @csrf           
                        <div class="form-group row">
                            <label for="task_name" class="col-sm-4 col-form-label text-md-right">{{ __('task_name') }}</label>
                            <div class="col-md-6">
                                <input id="task_name" type="text" class="form-control{{ $errors->has('task_name') ? ' is-invalid' : '' }}" name="task_name" value="{{ $task->task_name }}" required autofocus>

                                @if ($errors->has('task_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('task_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="task_description" class="col-sm-4 col-form-label text-md-right">{{ __('task_description') }}</label>
                            <div class="col-md-6">
                                <input id="task_description" type="text" class="form-control{{ $errors->has('task_description') ? ' is-invalid' : '' }}" name="task_description" value="{{ $task->task_description }}" required>

                                @if ($errors->has('task_description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('task_description') }}</strong>
                                    </span>
                                @endif                                
                            </div>
                        </div>
                        <div class="form-group row">
                                <label for="user_id" class="col-sm-4 col-form-label text-md-right">{{ __('user_id') }}</label>
                            <div class="col-md-6">
                                <select name="user_id[]" id="user_id" class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" value="{{ $task->user_id }}" required multiple="multiple">
                                <option disabled>Select One
                                @foreach($users as $user)
                                    <option value="{{$user['detail']->id}}" {{ $user['selected'] }}>{{$user['detail']->name}}</option>
                                @endforeach
                                </select>

                                @if ($errors->has('user_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                                @endif                                

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="task_date_assigned" class="col-md-4 col-form-label text-md-right">{{ __('task_date_assigned') }}</label>
                            <div class="col-md-6">
                                <input id="task_date_assigned" type="date" class="form-control{{ $errors->has('task_date_assigned') ? ' is-invalid' : '' }}" name="task_date_assigned" value="{{ $task->task_date_assigned }}" required>

                                @if ($errors->has('task_date_assigned'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('task_date_assigned') }}</strong>
                                    </span>
                                @endif                                

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="task_deadline" class="col-md-4 col-form-label text-md-right">{{ __('task_deadline') }}</label>
                            <div class="col-md-6">
                                <input id="task_deadline" type="date" class="form-control{{ $errors->has('task_deadline') ? ' is-invalid' : '' }}" name="task_deadline" value="{{ $task->task_deadline }}" required>

                                @if ($errors->has('task_deadline'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('task_deadline') }}</strong>
                                    </span>
                                @endif                                

                            </div>
                        </div>
                        <input name="_method" type="hidden" value="PUT">
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
    </div>
</div>