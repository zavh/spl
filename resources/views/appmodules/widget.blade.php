<div class="mb-0 rounded">
	<div class="media text-muted">
		<div class="media-body small">
			<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
				<strong class="text-dark pl-1 pt-1">
					Application Configuration Status
				</strong>
			</div>
            <div class="d-flex justify-content-between align-items-center w-100 border-bottom">
                <span class='m-2'>Salary Structure Configuration</span>
                <span class='m-2'>
                    @if(count($response['ss']) > 0)
                    <span class='badge badge-pill badge-success shadow-sm'>Configured</span>
                    @else
                    <span class='badge badge-pill badge-danger shadow-sm'>Not Configured</span>
                    @endif
                    <a href="/salarystructures/config" class="badge bg-light shadow-sm border">
                        ✍
                    </a>
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center w-100">
                <span class='m-2'>App Permission Configuration</span>
                <span class='m-2'>
                    @if(count($response['apc']) > 0)
                    <span class='badge badge-pill badge-success shadow-sm'>Configured</span>
                    @else
                    <span class='badge badge-pill badge-danger shadow-sm'>Not Configured</span>
                    @endif
                    <a href="/appmodules" class="badge bg-light shadow-sm border">
                        ✍
                    </a>
                </span>
            </div>
		</div>
	</div>
</div>