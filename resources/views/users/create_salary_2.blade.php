<!-- Disbursement Information Widget Starts -->
<div class="card mb-4 shadow-sm h-md-250">
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
                <!-- Pay Media Input Starts -->
                <div class="form-group row" style='margin-top:-10px'>
                        <div class="input-group input-group-sm col-md-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="font-size:12px;width:150px">Pay Out Mode</span>
                            </div>
                        <select name="pay_out_mode" id="pay_out_mode" class="form-control salary" required>
                        <option value="" disabled selected>Select One</option>
                        <option value="CASH" >Cash</option>
                        <option value="BANK" >Bank</option>
                        </select>
                    </div>
                </div>
                <!-- Pay Media Input End -->
                <!-- Account Name Input Starts -->
                <div class="form-group row">
                    <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Account Name</span>
                        </div>
                        <input id="bank_account_name" type="text" class="form-control salary" name="bank_account_name" value="">
                        <span class="invalid-feedback" role="alert" id="bank_account_name_error_span">
                            <strong id="bank_account_name_error"></strong>
                        </span>
                    </div>
                </div>
                <!-- Account Name Input Ends -->
                <!-- Account Number Input Starts -->
                <div class="form-group row">
                    <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Account Number</span>
                        </div>
                        <input id="bank_account_number" type="text" class="form-control salary" name="bank_account_number" value="">
                        <span class="invalid-feedback" role="alert" id="bank_account_number_error_span">
                            <strong id="bank_account_number_error"></strong>
                        </span>
                    </div>
                </div>
                <!-- Account Number Input Ends -->
                <!-- Bank Name Input Starts -->
                <div class="form-group row">
                    <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Bank Name</span>
                        </div>
                        <input id="bank_name" type="text" class="form-control salary" name="bank_name" value="" >
                        <span class="invalid-feedback" role="alert" id="bank_name_error_span">
                            <strong id="bank_name_error"></strong>
                        </span>   
                    </div>
                </div>
                <!-- Bank Name Input Ends -->
                <!-- Branch Input Starts -->
                <div class="form-group row">
                    <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Branch</span>
                        </div>
                        <input id="bank_branch" type="text" class="form-control salary" name="bank_branch" value="">
                        <span class="invalid-feedback" role="alert" id="bank_branch_error_span">
                            <strong id="bank_branch_error"></strong>
                        </span>
                    </div>
                </div>
                <!-- Branch Input Ends -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Disbursement Information Widget Ends -->