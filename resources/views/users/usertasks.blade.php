<div class=" mb-0 bg-white rounded">
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
					<form>
					{{csrf_field()}}
					<input type='checkbox' name='done-{{$task->id}}'>&nbsp;Mark task as Done on &nbsp; <input type='date' name='done-date-{{$task->id}}' min='{{$task->task_date_assigned}}' disabled>
					</form>
				</div>
			@endforeach
		</div>
	</div>
</div>