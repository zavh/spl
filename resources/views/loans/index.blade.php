@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row" >
            <div class="col-md-12 col-lg-8">
                <div class="card mb-4 shadow-sm h-md-250">
				<div class=" mb-0 bg-white rounded">
					<div class="media text-muted">
						<div class="media-body small">
							<div class="d-flex justify-content-between align-items-center w-100 border-bottom">
								<strong class="text-dark pl-1 pt-1">List of Configured Loans</strong>
                                <a href="/departments/create" class="pr-2 pt-1">Create new Loan</a>
							</div>
                            <strong class="d-inline-block mb-2 text-primary">List of Loans</strong>                           
                            <div class="row m-0 bg-light border-bottom w-100">
                                @foreach($users as $user)
                                    <div class="text-primary pl-1 text-success"> User name: <strong>{{$user->name}}</strong> </div>
                                    @foreach ($user->salary->loans as $loan)
                                        <div class="text-primary pl-1 text-success"> Loan Amount: <strong>{{$loan->amount}}</strong> </div>
                                        <div class="text-primary pl-1 text-success"> Loan Start: <strong>{{$loan->start_date}}</strong> </div>
                                        <div class="text-primary pl-1 text-success"> Loan End: <strong>{{$loan->end_date}}</strong> </div>
                                        <div class="text-primary pl-1 text-success"> Number of Installments: <strong>{{$loan->installments}}</strong> </div>
                                        <div class="text-primary pl-1 text-success"> Loan Type: <strong>{{$loan->loan_type}} </strong> </div>
                                        <div class="text-primary pl-1 text-success"> Interest Rate: <strong>{{$loan->interest}} %</strong> </div>
                                        <div class="text-primary pl-1 text-success"> Creation Date: <strong>{{$loan->created_at}}</strong> </div>
                                        <div class="text-primary pl-1 text-success"> End Date: <strong>{{$loan->updated_at}}</strong> </div>
                                    @endforeach
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