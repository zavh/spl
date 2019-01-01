<!-- Salary Widget Starts -->
<div class="card mb-4 shadow-sm h-md-250">
    <div class=" mb-0 bg-white rounded">
        <div class="media text-muted">
            <div class="media-body small mb-0">
                <div class="d-flex justify-content-center align-items-center w-100 border-bottom">
                    <!-- Header Starts -->
                    <span class="text-dark">
                        <strong>Salary Information</strong>
                    </span>
                </div>
                <div class="card-body pb-0">
                <!-- Basic Salary Input Starts -->
                <div class="form-group row" style='margin-top:-10px'>
                    <div class="input-group input-group-sm col-md-12" >
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Basic Salary</span>
                        </div>
                        <input id="basic" type="text" class="form-control salary" name="basic" value="" required>
                        <span class="invalid-feedback" role="alert" id="basic_error_span">
                            <strong id="basic_error"></strong>
                        </span>
                    </div>
                </div>
                <!-- Basic Salary Input Ends -->
                <!-- Loan Input Starts -->
                <div class="form-group row" style='margin-top:-10px'>
                    <div class="input-group input-group-sm col-md-12" >
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Loan</span>
                        </div>
                        <input id="loan" type="text" class="form-control salary" name="loan" value="">
                        <span class="invalid-feedback" role="alert" id="loan_error_span">
                            <strong id="loan_error"></strong>
                        </span>
                    </div>
                </div>
                <!-- Loan Input Ends -->
                </div>
            </div>
        </div>
    </div>
</div>