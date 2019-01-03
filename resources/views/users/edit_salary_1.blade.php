<!-- Disbursement Widget Starts -->
<div class="card mb-4 shadow-sm h-md-250 row-md-6">
    <div class=" mb-0 bg-white rounded">
        <div class="media text-muted">
            <div class="media-body small mb-0">
                <div class="d-flex justify-content-center align-items-center w-100 border-bottom">
                    <!-- Header Starts -->
                    <span class="text-dark">
                        <strong>Disbursement Information</strong>
                    </span>
                </div>
                <div class="card-body pb-0">
                <!-- Basic Salary Input Starts -->
                    <div class="form-group row" style='margin-top:-10px'>
                        <div class="input-group input-group-sm col-md-12" >
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="font-size:12px;width:150px">Basic Salary</span>
                            </div>
                            <input id="basic" type="text" class="form-control salary" name="basic" value="{{ $salaryinfo->basic }}" required>
                            <span class="invalid-feedback" role="alert" id="basic_error_span">
                                <strong id="basic_error"></strong>
                            </span>
                        </div>
                    </div>
                    <!-- Basic Salary Input Ends -->

                    <!-- Joining Date Input Starts -->
                    <div class="form-group row">
                        <div class="input-group input-group-sm col-md-12" style='margin-top:-10px'>
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="font-size:12px;width:150px">Joining Date</span>
                            </div>
                            <input id="join_date" type="date" class="form-control salary" name="join_date" value="{{ $salaryinfo->join_date }}" required>
                            <span class="invalid-feedback" role="alert" id="join_date_error_span">
                                <strong id="join_date_error"></strong>
                            </span>
                        </div>
                    </div>
                    <!-- Joining Date Input Ends -->

                    <!-- Termination Date Input Starts -->
                    <div class="form-group row">
                        <div class="input-group input-group-sm col-md-12" style='margin-top:-10px'>
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="font-size:12px;width:150px">Termination status</span>
                            </div>
                            <div class="input-group-prepend">
                                @if ($salaryinfo->tstatus == 'a')
                                    Active:<input type='radio' class="form-control salary" id="tstatus" name='tstatus' value='a' checked>
                                    Resigned:<input type='radio' class="form-control salary" id="tstatus" name='tstatus' value='r'>
                                    Terminated:<input type='radio' class="form-control salary" id="tstatus" name='tstatus' value='t'>
                                @elseif($salaryinfo->tstatus == 'r')
                                    Active:<input type='radio' class="form-control salary" id="tstatus" name='tstatus' value='a'>
                                    Resigned:<input type='radio' class="form-control salary" id="tstatus" name='tstatus' value='r' checked>
                                    Terminated:<input type='radio' class="form-control salary" id="tstatus" name='tstatus' value='t'>
                                @else
                                    Active:<input type='radio' class="form-control salary" id="tstatus" name='tstatus' value='a'>
                                    Resigned:<input type='radio' class="form-control salary" id="tstatus" name='tstatus' value='r'>
                                    Terminated:<input type='radio' class="form-control salary" id="tstatus" name='tstatus' value='t' checked>
                                @endif
                            </div>
                            <span class="invalid-feedback" role="alert" id="tstatus_error_span">
                                <strong id="tstatus_error"></strong>
                            </span>
                                
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="font-size:12px;width:150px">Termination Date</span>
                            </div>
                            <input id="end_date" type="date" class="form-control salary" name="end_date" value="{{ $salaryinfo->end_date }}">
                            <span class="invalid-feedback" role="alert" id="end_date_error_span">
                                <strong id="end_date_error"></strong>
                            </span>
                        </div>
                    </div>
                    <!-- Termination Date Input Ends -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Salary Widget Starts -->
<div class="card mb-4 shadow-sm h-md-250 row-md-6">
    <div class=" mb-0 bg-white rounded">
        <div class="media text-muted">
            <div class="media-body small mb-0">
                <div class="d-flex justify-content-center align-items-center w-100 border-bottom">
                    <!-- Header Starts -->
                    <span class="text-dark">
                        <strong>TDS Calculation</strong>
                    </span>
                </div>
                <div class="card-body pb-0">
                    <!-- DOB Input Starts -->
                    <div class="form-group row">
                        <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="font-size:12px;width:150px">Date of Birth</span>
                            </div>
                            <input id="date_of_birth" type="date" class="form-control salary" name="date_of_birth" value="{{ $salaryinfo->date_of_birth }}" required>
                            <span class="invalid-feedback" role="alert" id="date_of_birth_error_span">
                                <strong id="date_of_birth_error"></strong>
                            </span>
                        </div>
                    </div>
                    <!-- DOB Input Ends -->

                    <!-- Gender Input Starts -->
                    <div class="form-group row" style='margin-top:-10px'>
                            <div class="input-group input-group-sm col-md-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="font-size:12px;width:150px">Gender</span>
                                </div>
                            <select name="gender" id="gender" class="form-control salary" required>
                            <option value="" disabled selected>Select One</option>
                            <option value="m" {{$salaryinfo->gender == "m" ? 'selected' : ''}}>Male</option>
                            <option value="f" {{$salaryinfo->gender == "f" ? 'selected' : ''}}>Female</option>
                            <option value="o" {{$salaryinfo->gender == "o" ? 'selected' : ''}}>Other</option>
                            </select>
                        </div>
                    </div>
                <!-- Gender Input End -->
                </div>
            </div>
        </div>
    </div>
</div>