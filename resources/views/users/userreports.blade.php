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
				<div class="row m-0 pl-2 bg-light border-bottom w-100">
					Visit Report of : &nbsp;
					<a href="/reports/{{$report['id']}}/edit" class='text-success'>
                        <strong class='text-dark'>{{$report['data']->client_data->organization}}&nbsp;</strong>
                    </a>
				</div>
			@endforeach
            <div class='bg-success text-white'>Completed Reports</div>
		</div>
	</div>
</div>