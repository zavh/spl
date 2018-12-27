@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-light text-dark m-0 p-1 pl-2">
                    {{ __('Edit Salary Structure') }}
                </div>
                <div class="card-body pb-0 mb-0">
                    <form method="POST" action="{{ route('salarystructures.update', [$salarystructure->id]) }}">
                        @csrf
                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name='page' value='{{$page}}'>

                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:160px'>{{ __('Structure Name') }}</span>
                                </div>
                                <input id="structurename" type="text" class="form-control{{ $errors->has('structurename') ? ' is-invalid' : '' }}" name="structurename" value="{{ $salarystructure->structurename }}" required>

                                @if ($errors->has('structurename'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('structurename') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Organization Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:160px'>{{ __('House Rent') }}</span>
                                </div>
                                <input id="houserent" type="text" class="form-control{{ $errors->has('houserent') ? ' is-invalid' : '' }}" name="houserent" value="{{ $salarystructure->houserent }}" required>

                                @if ($errors->has('houserent'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('houserent') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Organization Name Input Ends -->
                        <!-- Address Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:160px'>{{ __('Medical Allowance') }}</span>
                                </div>
                                <input id="medicalallowance" type="text" class="form-control{{ $errors->has('medicalallowance') ? ' is-invalid' : '' }}" name="medicalallowance" value="{{ $salarystructure->medicalallowance }}" required>

                                @if ($errors->has('medicalallowance'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('medicalallowance') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                    
                        <!-- Address Input Ends -->                        
                        <!-- Contact Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:160px'>{{ __('Conveyance') }}</span>
                                </div>
                                <input id="conveyance" type="text" class="form-control{{ $errors->has('conveyance') ? ' is-invalid' : '' }}" name="conveyance" value="{{ $salarystructure->conveyance }}" required>

                                @if ($errors->has('conveyance'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('conveyance') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Contact Name Input Ends -->
                        <!-- Contact Designation Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:160px'>{{ __('Provident Fund(Company)') }}</span>
                                </div>
                                <input id="pf_company" type="text" class="form-control{{ $errors->has('pf_company') ? ' is-invalid' : '' }}" name="pf_company" value="{{ $salarystructure->providentfundcompany }}" required>

                                @if ($errors->has('pf_company'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pf_company') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Contact Designation Input Ends -->                        
                        <!-- Client Contact Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='width:160px'>{{ __('Provident Fund(Self)') }}</span>
                                </div>
                                <input id="pf_self" type="text" class="form-control{{ $errors->has('pf_self') ? ' is-invalid' : '' }}" name="pf_self" value="{{ $salarystructure->providentfundself }}" required>

                                @if ($errors->has('pf_self'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pf_self') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Client Contact Input Ends -->                   
                        <div class='form-group row mt-2'>
                            <div class="input-group input-group-sm col-md-6">
                                <input type="submit" class="btn btn-outline-primary btn-sm btn-block" value="Edit">
                            </div>
                            <div class="input-group input-group-sm col-md-6">
                            <a href="{{ $page == null ? '/salarystructures' : '/salarystructures/create'}}" class="btn btn-outline-secondary btn-sm btn-block">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection