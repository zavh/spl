<form action="" class='m-1 p-1 small' autocomplete="off" id="findreports" name='findreports' onsubmit='findProjects(event, this)'>
    <div class="form-group">
        <label for="formGroupExampleInput">Search by Date</label>
        <div class="row">
            <div class="col">
            <input type="date" name='projectmonthstart' id='projectmonthstart' class="ploinput form-control form-control-sm"
            placeholder="Start Date" onchange="dateSearchCriteria(this,1)">
            <span class="invalid-feedback" role="alert" id="projectmonthstart_error_span">
                <strong id="projectmonthstart_error"></strong>
            </span>
            </div>
            <div class="col">
            <input type="date" class="form-control form-control-sm" value="" placeholder="End Date" id='dummyprojectmonthend' onchange="dateSearchCriteria(this,0)">
            <input type="hidden" name='projectmonthend' id='projectmonthend' class="ploinput form-control">
            <span class="invalid-feedback" role="alert" id="projectmonthend_error_span">
                <strong id="projectmonthend_error"></strong>
            </span>
            </div>
        </div>
    </div>

    <div class="input-group input-group-sm">
        <strong class="col-md-4">Search by Client:</strong>
        <input type="text" name='projectclient' id='projectclient' class="ploinput form-control" value=""
            placeholder="Search Project" aria-label="Project Client" aria-describedby="button-addon2">
        
    </div>
        <span class="invalid-feedback" role="alert" id="projectclient_error_span">
            <strong id="projectclient_error"></strong>
        </span>

        
    <div class="input-group input-group-sm">
        <strong class="col-md-4">Search by Manager:</strong>
        <input type="text" name='projectmanager' id='projectmanager' class="ploinput form-control" value=""
            placeholder="Search Project" aria-label="Project Manager" aria-describedby="button-addon2">
                
    </div>
        <span class="invalid-feedback" role="alert" id="projectmanager_error_span">
            <strong id="projectmanager_error"></strong>
        </span>
    
        <div class="input-group-append">
        <button class="btn btn-secondary btn-sm" type="submit" id="button-addon2">Go</button>
    </div>
</form>