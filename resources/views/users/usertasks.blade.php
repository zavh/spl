<div class="mb-0 bg-white rounded">
	<div class="media text-muted">
		<div class="media-body small">
			<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
				<strong class="text-dark pl-1 pt-1">
					My Open Tasks
				</strong>
				<a href="#" class="pr-2 pt-1">Completed Tasks</a>
			</div>
			@foreach($tasks as $index=>$task)
				<div class="row m-0 pl-2 bg-light border-bottom w-100">
					Task:<strong class='text-dark'>&nbsp;{{$task->task_name}}&nbsp;</strong> for 
					<a href='/projects/{{$task->project_id}}' class='text-success'>&nbsp;{{$task->project_name}}</a>
				</div>
				<div class="row m-0 pl-4 pt-2 bg-light border-bottom w-100">
					<form name='done-form-{{$task->id}}' id='done-form-{{$task->id}}' method="POST" action="/tasks/completion/{{$task->id}}">
						{{csrf_field()}}
						<input type="hidden" id="task_date_assigned" name="task_date_assigned" value="{{$task->task_date_assigned}}">
						@if ($task->completed == 0)
							<input type='checkbox' onclick="updateTask('{{$task->id}}')" name='done-{{$task->id}}' id="done-{{$task->id}}" unchecked>
							&nbsp;Mark task as Done on &nbsp;
							<input type='date' name='done-date-{{$task->id}}' id='done-date-{{$task->id}}' min='{{$task->task_date_assigned}}' value="">
						@else
							<input type='checkbox' onclick="updateTask('{{$task->id}}')" name='done-{{$task->id}}' id="done-{{$task->id}}" checked>
							&nbsp;Mark task as Done on &nbsp;
							<input type='date' name='done-date-{{$task->id}}' id='done-date-{{$task->id}}' min='{{$task->task_date_assigned}}' value="{{$task->date_completed}}">
						@endif
						 
						
					</form>
				</div>
			@endforeach
		</div>
	</div>
</div>