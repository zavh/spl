@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row" >
            <div class="col-md-12 col-lg-10">
                <div class="card mb-4 shadow-sm h-md-250">
				<div class=" mb-0 bg-white rounded">
					<div class="media text-muted">
						<div class="media-body small">
							<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
								<strong class="text-dark pl-1 pt-1">List of configured Loans</strong>
								<a href="/loans/create" class="pr-2 pt-1">Create New Loan</a>
							</div>
                            <strong class="d-inline-block mb-2 text-primary">List of Loans</strong>                           
                            <div class="row m-0 bg-light border-bottom w-100">
                                @foreach($users as $user)
                                    @if($user->salary->loans != '[]')
                                        <div class="text-primary pl-1 text-success"> User name: <strong>{{$user->name}}</strong> </div><br/>
                                        @foreach ($user->salary->loans as $loan)
                                            <div class="text-primary pl-1 text-success"> Loan Amount: <strong>{{$loan->amount}}</strong> </div>
                                            <div class="text-primary pl-1 text-success"> Loan Start: <strong>{{$loan->start_date}}</strong> </div>
                                            <div class="text-primary pl-1 text-success"> Loan End: <strong>{{$loan->end_date}}</strong> </div>
                                            <div class="text-primary pl-1 text-success"> Number of Installments: <strong>{{$loan->installments}}</strong> </div>
                                            <div class="text-primary pl-1 text-success"> Loan Type: <strong>{{$loan->loan_type}} </strong> </div>
                                            <div class="text-primary pl-1 text-success"> Interest Rate: <strong>{{$loan->interest}} %</strong> </div>
                                            <div class="text-primary pl-1 text-secondary"> <a href="/loans/{{$loan->id}}/edit" class="pr-2 pt-1">Edit</a></div>
                                        @endforeach
                                    @endif
                                @endforeach     
                            </div>                                          
                        </div>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection