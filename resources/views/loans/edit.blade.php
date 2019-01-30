@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-primary">
                    {{ __('Create new Loan') }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('loans.update', [$loan->id]) }}" style='font-size:10px'>
                        @csrf
                        <!-- Organization Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('User') }}</span>
                                </div>
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Loan') }}</span>
                                </div>
                                <input id="loanname" type="text" class="form-control{{ $errors->has('loanname') ? ' is-invalid' : '' }}" name="loanname" value="{{ old('loanname') }}" required>

                                @if ($errors->has('loanname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loanname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('amount') }}</span>
                                </div>
                                <input id="amount" type="text" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ old('amount') }}" required>

                                @if ($errors->has('amount'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('start_date') }}</span>
                                </div>
                                <input id="start_date" type="date" class="form-control{{ $errors->has('start_date') ? ' is-invalid' : '' }}" name="start_date" value="{{ old('start_date') }}" required>

                                @if ($errors->has('start_date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('end_date') }}</span>
                                </div>
                                <input id="end_date" type="date" class="form-control{{ $errors->has('end_date') ? ' is-invalid' : '' }}" name="end_date" value="{{ old('end_date') }}" required>

                                @if ($errors->has('end_date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('end_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('installments') }}</span>
                                </div>
                                <input id="installments" type="number" class="form-control{{ $errors->has('installments') ? ' is-invalid' : '' }}" name="installments" value="{{ old('installments') }}" required>

                                @if ($errors->has('installments'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('installments') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('loan_type') }}</span>
                                </div>
                                <input id="loan_type" type="text" class="form-control{{ $errors->has('loan_type') ? ' is-invalid' : '' }}" name="loan_type" value="{{ old('loan_type') }}" required>
                                {{-- add a foreach select --}}

                                @if ($errors->has('loan_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('loan_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('interest') }}</span>
                                </div>
                                <input id="interest" type="text" class="form-control{{ $errors->has('interest') ? ' is-invalid' : '' }}" name="interest" value="{{ old('interest') }}" required>

                                @if ($errors->has('interest'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('interest') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Organization Name Input Ends -->
                    
                        <div class='row d-flex justify-content-center'> 
                            <button type="submit" class="btn btn-primary btn-block btn-sm">
                                {{ __('Create') }}
                            </button>
                            <a href="/loans" class="btn btn-primary btn-block btn-sm">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection