<div id='client-contact-edit'></div>
<div class="accordion" id="clientContactAccordion">
@foreach($contacts as $contact)
	<div id='contact-item-{{$contact->id}}'>
	<div class="media text-muted" id="heading{{$contact->id}}">
		<div class="media-body pb-0 mb-0 small lh-125">
			<div class="d-flex justify-content-between align-items-center w-100 border-bottom mb-0 pb-0 pr-2 bg-light">
				<button 
					class="btn btn-link btn-sm" 
					type="button" 
					data-toggle="collapse" 
					data-target="#collapse{{$contact->id}}" 
					aria-expanded="false" 
					aria-controls="collapse{{$contact->id}}" 
					onclick="document.getElementById('client-contact-edit').innerHTML=''"
					>
				{{$contact->name}}
				</button>
				<a 
					href="javascript:void(0)"
					onclick="ajaxFunction('showEditClientContact', '{{$contact->id}}', 'client-contact-edit')">Edit</a>
			</div>
		</div>
	</div>
    
    <div id="collapse{{$contact->id}}" class="collapse" aria-labelledby="heading{{$contact->id}}" data-parent="#clientContactAccordion">
	    <div class="small ml-4 mr-4 border-bottom text-success">
        Designation : <span contenteditable="true" spellcheck="false">{{$contact->designation}}</span>
        </div>
        <div class="small ml-4 mr-4 border-bottom text-success">
        Contact : {{$contact->contact}}
        </div>
    </div>
	</div>
@endforeach
</div>