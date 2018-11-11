@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header text-white bg-primary">
                    {{ __('Edit Department') }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('departments.update', [$department->id]) }}" style='font-size:10px'>
                        <input name="_method" type="hidden" value="PUT">
                        @csrf
                        <!-- Organization Name Input Starts -->
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Name') }}</span>
                                </div>
                                <input id="organization" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $department->name }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Organization Name Input Ends -->
                    
                        <div class='row d-flex justify-content-center'> 
                            <button type="submit" class="btn btn-primary btn-block btn-sm">
                                {{ __('Submit') }}
                            </button>
                            <a href="/departments" class="btn btn-primary btn-block btn-sm">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection