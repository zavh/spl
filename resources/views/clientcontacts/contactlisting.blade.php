<div class="card mb-4 shadow-sm h-md-250">
	<div class=" mb-0 bg-white rounded">
		<div class="media text-muted">
			<div class="media-body small">
				<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
					<strong class="text-dark pl-1 pt-1">Choose a contact</strong>
					<a href="#" class='pr-2'>Add contact</a>
				</div>
				<div class="accordion" id="clientContactAccordion">
				@foreach($contacts as $index=>$contact)
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
									>
								{{$contact->name}}
								</button>
								<input 
									type='checkbox' 
									name='tempcontact'
									data-cid = "{{$contact->client_id}}"
									data-id="{{$contact->id}}"
									data-name="{{$contact->name}}"
									data-contact="{{$contact->contact}}" 
									data-designation="{{$contact->designation}}" 
									data-index = "{{$index}}"
									onclick = "selectClientContact(this)"
									>
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
			</div>
		</div>
	</div>
</div>