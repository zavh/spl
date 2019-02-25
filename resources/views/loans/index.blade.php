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
								<strong class="text-dark pl-1 pt-1">List of configured Loans</strong>
								<a href="/loans/create" class="pr-2 pt-1">Create New Loan</a>
							</div>
                            <strong class="d-inline-block mb-2 text-primary">List of Loans</strong>                           
                            <div class="row m-0 bg-light border-bottom w-100">
                                @foreach($users as $user)
                                    @if($user->salary->loans != '[]')
                                        <div class="col-lg-2 text-primary pl-1 text-success"> User name: <strong>{{$user->name}}</strong> </div><br/>
                                        @foreach ($user->salary->loans as $loan)
                                            <div class="col-lg-1 text-primary pl-1 text-success"> Type: <strong>{{$loan->loan_name}}</strong> </div>
                                            <div class="col-lg-1 text-primary pl-1 text-success"> Amount: <strong>{{$loan->amount}}</strong> </div>
                                            <div class="col-lg-2 text-primary pl-1 text-success"> Start: <strong>{{$loan->start_date}}</strong> </div>
                                            <div class="col-lg-2 text-primary pl-1 text-success"> End: <strong>{{$loan->end_date}}</strong> </div>
                                            <div class="col-lg-1 text-primary pl-1 text-success"> Installments: <strong>{{$loan->installments}}</strong> </div>
                                            <div class="col-lg-1 text-primary pl-1 text-success"> Interest: <strong>{{$loan->interest}} %</strong> </div>
                                            <div class="col-lg-1 text-primary pl-1 text-secondary"> <a href="/loans/{{$loan->id}}/edit" class="badge-success badge padge-pill">Edit</a></div>
                                            <div class="col-lg-1 text-primary pl-1 text-secondary">
                                                <a href="javascript:void(0)" onclick="deleteLoan('{{$loan->loan_name}}','{{$loan->id}}')" class='badge-danger badge padge-pill'>Delete</a>
                                            </div>
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
<script src="{{ asset('js/loans.js?version=0.1') }}"></script>