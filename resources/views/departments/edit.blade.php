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
                        <div class="form-group row">
                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ __('Parent Department') }}</span>
                                </div>
                                <select name="parent_id" id="parent_id" class="form-control">
                                <option value="0" {{ $department->parent_id == 0 ? 'selected' : '' }}>Sigma Group</option>
                                @foreach($departments as $dpt)
                                <option value="{{$dpt->id}}" {{ $department->parent_id == $dpt->id ? 'selected' : '' }}>{{$dpt->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
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
                        <div class="form-group row mt-2">
                            <div class="input-group input-group-sm col-md-6">
                                <input type="submit" class="btn btn-outline-primary btn-sm btn-block" value="Update">
                            </div>
                            <div class="input-group input-group-sm col-md-6">
                            <a href="/departments" class="btn btn-outline-secondary btn-sm btn-block">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
</div>
@endsection