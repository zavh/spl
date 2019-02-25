@foreach($projects as $project)
<div class="">
    <div class='text-left bg-light border-bottom border-top'>
        <span class="mx-2">Client: <strong>{{$project->client->organization}}</strong></span>
    </div>
    <p class='text-left mx-4 mb-0 pb-0'>
        Project title: <a href="/projects/{{$project->id}}">{{$project->project_name}}</a>
    </p>
    
    <div class="d-flex justify-content-between align-items-center border-top mb-0 mx-4">
    @if($status == 'open')
        <span>Allocation: {{$project->allocation}}%</span>
        <span>Completion: {{$project->completed}}%</span>
    @elseif($status == 'expired')
        <span class='text-danger'>Expired on {{$project->deadline}}</span>
    @elseif($status == 'closed')
        @if($project->status == 1)
        <span class='text-danger'>Project Status : Lost</span>
        @elseif($project->status == 2)
        <span class='text-success'>Project Status : Won</span>
        @endif
    @endif
    </div>
</div>
@endforeach