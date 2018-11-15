<div class="container-fluid p-0 m-0">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card shadow-sm p-2">
            <form method="POST" action="" style='font-size:10px' onsubmit="visitReportDetails(event, this);" name='visitReportDetails' id='visitReportDetails'>
                        @csrf
            <!-- Visit day input starts -->
                <div class="form-group row">
                    <div class="input-group col-md-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Date of visit</span>
                        </div>
                        <input type='date' name='visit-date' id='visit-date' class="form-control form-control" required>
                        <span class="invalid-feedback " role="alert" id="visit-date_error_span" style="display: none;">
                            <strong id="visit-date_error"></strong>
                        </span>
                    </div>
                </div>
            <!-- Visit day input ends -->
            <!-- Meeting Issue input starts -->
                <div class="form-group row">
                    <div class="input-group col-md-12" style="margin-top:-10px">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Meeting Issue</span>
                        </div>
                        <textarea id="meeting-issue" class="form-control" aria-label="With textarea" name="meeting-issue" required=""></textarea>
                        <span class="invalid-feedback " role="alert" id="meeting-issue_error_span" style="display: none;">
                            <strong id="meeting-issue_error"></strong>
                        </span>
                    </div>
                </div>
            <!-- Meeting Issue input ends -->
            <!-- Requirement details input starts -->
                <div class="form-group row">
                    <div class="input-group col-md-12" style="margin-top:-10px">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Requirement Details</span>
                        </div>
                        <textarea id="requirement-details" class="form-control" aria-label="With textarea" name="requirement-details" required=""></textarea>
                        <span class="invalid-feedback " role="alert" id="requirement-details_error_span" style="display: none;">
                            <strong id="requirement-details_error"></strong>
                        </span>
                    </div>
                </div>
            <!-- Requirement details input ends -->
            <!-- Product discussed input ends -->
                <div class="form-group row">
                    <div class="input-group col-md-12" style="margin-top:-10px">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Product Discussed</span>
                        </div>
                        <textarea id="product-discussed" class="form-control" aria-label="With textarea" name="product-discussed" required=""></textarea>
                        <span class="invalid-feedback " role="alert" id="product-discussed_error_span" style="display: none;">
                            <strong id="product-discussed_error"></strong>
                        </span>
                    </div>
                </div>
            <!-- Product discussed input ends -->
                <div class="form-group row">
                    <div class="input-group col-md-12" style="margin-top:-10px">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Outcome in brief</span>
                        </div>
                        <textarea id="meeting-issue" class="form-control" aria-label="With textarea" name="meeting-issue" required=""></textarea>
                        <span class="invalid-feedback " role="alert" id="meeting-issue_error_span" style="display: none;">
                            <strong id="meeting-issue_error"></strong>
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="input-group col-md-12" style="margin-top:-10px">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Remarks, if any</span>
                        </div>
                        <textarea id="meeting-issue" class="form-control" aria-label="With textarea" name="meeting-issue" required=""></textarea>
                        <span class="invalid-feedback " role="alert" id="meeting-issue_error_span" style="display: none;">
                            <strong id="meeting-issue_error"></strong>
                        </span>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>