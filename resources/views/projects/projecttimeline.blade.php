<ul class="timeline" id="timeline">
<li class="li complete">
    <div class="timestamp">
      <span class="author">{{$project->user->name}}</span>
      <span class="date small">{{date('d M \'y',strtotime($project->start_date))}}<span>
    </div>
    <div class="status">
      <span class='heading'>Project Started</span>
    </div>
  </li>
  @foreach($project->tasks as $task)
  <li class="li {{$task->completed==1?'complete':''}}">
    <div class="timestamp">
      <span class="author">
        @if(count($task->users)>1)
            Multiple
        @else 
            {{$task->users->first()->name}}
        @endif
      </span>
      <span class="date small">{{date('d M \'y',strtotime($task->task_deadline))}}<span>
    </div>
    <div class="status">
      <span class='heading'>{{$task->task_name}}</span>
    </div>
  </li>
  @endforeach
  <li class="li">
    <div class="timestamp">
      <span class="author">Completion</span>
      <span class="date small">{{date('d M \'y',strtotime($project->deadline))}}<span>
    </div>
    <div class="status">
      <span class='heading'>Deadline</span>
    </div>
  </li>
 </ul>