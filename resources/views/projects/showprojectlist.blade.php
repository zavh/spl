<div class='col-12'>
    <div class=" mb-0 bg-white rounded">
        <div class="media text-muted">
            <div class="media-body small">
                <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                    <strong class="text-dark pl-1 pt-1">{{$title}}</strong>
                </div>
                <div class="text-primary p-0 text-secondary">
                    <div class="accordion" id="projectcurrentAccordion">
                        @foreach ($searched_project as $i=>$project)
                            <div id='contact-item-{{$i}}'>
                                <div class="media text-muted" id="heading{{$i}}">
                                    <div class="media-body pb-0 mb-0 small lh-125">
                                        <div class="d-flex justify-content-between align-items-center w-100 border-bottom mb-0 pb-0 pr-2 bg-light">
                                            @if ($project->status == 1){{-- lost --}}
                                                <a href="/projects/{{$project->id}}" style="color: red">{{$project->project_name}}</a>
                                            @else
                                                <a href="/projects/{{$project->id}}" style="color: blue">{{$project->project_name}}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        @endforeach
                    </div>      
                </div>
            </div>      
        </div>
    </div>
</div>