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
                        <input 
                            type='date' 
                            name='visit_date' 
                            id='visit_date' 
                            class="form-control form-control" 
                            placeholder='YYYY-mm-dd'
                            onchange='stage2State()'
                            required
                            @isset($stage2["visit_date"])
                                value="{{$stage2['visit_date']}}"
                            @else 
                                value=""
                            @endisset
                            >
                        <span class="invalid-feedback " role="alert" id="visit_date_error_span" style="display: none;">
                            <strong id="visit_date_error"></strong>
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
                        <textarea 
                            id="meeting_issue" 
                            class="form-control" 
                            aria-label="With textarea" 
                            name="meeting_issue"
                            @isset($stage2["visit_date"])
                                value="{{$stage2['meeting_issue']}}"
                            @else 
                                value=""
                            @endisset                            
                            >
                        </textarea>
                        <span class="invalid-feedback " role="alert" id="meeting_issue_error_span" style="display: none;">
                            <strong id="meeting_issue_error"></strong>
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
            <!-- Outcome input starts -->
                <div class="form-group row">
                    <div class="input-group col-md-12" style="margin-top:-10px">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Outcome in brief</span>
                        </div>
                        <textarea id="outcome-brief" class="form-control" aria-label="With textarea" name="outcome-brief" required=""></textarea>
                        <span class="invalid-feedback " role="alert" id="outcome-brief_error_span" style="display: none;">
                            <strong id="outcome-brief_error"></strong>
                        </span>
                    </div>
                </div>
            <!-- Outcome input ends -->
            <!-- Remarks input starts -->
                <div class="form-group row">
                    <div class="input-group col-md-12" style="margin-top:-10px">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size:12px;width:150px">Remarks, if any</span>
                        </div>
                        <textarea id="remarks" class="form-control" aria-label="With textarea" name="remarks"></textarea>
                        <span class="invalid-feedback " role="alert" id="remarks_error_span" style="display: none;">
                            <strong id="remarks_error"></strong>
                        </span>
                    </div>
                </div>
            <!-- Remarks input ends -->
                <div class="d-flex justify-content-between border border-primary badge badge-pill w-100">
                    
                        <span class='text-primary'> 
                            <a class='btn btn-primary rounded-circle text-white' onclick="backValidation()">
                            <
                            </a>
                            &nbsp;Back
                        </span>
                        <span class='text-primary'> 
                            <a class='btn btn-primary rounded text-white' onclick="saveStage(2)">
                            Save
                            </a>
                        </span>
                        <span class='text-primary'> 
                            Submit
                            <button type='submit' class='btn btn-primary rounded-circle' onclick="">
                            >
                            </button>
                        </span>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>