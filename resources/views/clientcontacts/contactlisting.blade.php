<div class=" mb-0 bg-white rounded">
	<div class="media text-muted">
		<div class="media-body small">
			<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
				<strong class="text-dark pl-1 pt-1">Choose an existing client</strong>
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
								name='contact[{{$index}}]'
								data-name = "{{$contact->name}}"
								onclick = "selectClientContat(this)"
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