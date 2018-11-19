<div class="mb-0 rounded">
	<div class="media text-muted">
		<div class="media-body small">
			<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
				<strong class="text-dark pl-1 pt-1">
					My Visit Reports
				</strong>
				<a href="/reports/create" class="pr-2 pt-1">Create Report</a>
			</div>
            <div class='bg-warning text-dark'>Incomplete Reports</div>
			@foreach($reports as $index=>$report)
				<div class="d-flex justify-content-between align-items-center w-100 border-bottom pl-1 pr-1">
					<span>Visit Report of :
					<a href="/reports/{{$report['id']}}/edit" class='text-success'>
                        <strong class='text-dark'>{{$report['data']->client_data->organization}}</strong>
                    </a></span>
					<a href="javascript:void(0)" class='badge badge-pill badge-light shadow-sm border' onclick="deleteReport('{{$report['id']}}')">x</a>
				</div>
			@endforeach
            <div class='bg-success text-white'>Completed Reports</div>
		</div>
	</div>
</div>