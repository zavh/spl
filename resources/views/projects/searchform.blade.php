<div class='m-2 border border-success'>
    <form action="" class='m-1 p-1 small' autocomplete="off" id="findreports" name='findreports' onsubmit='findProjects(event, this)'>
        <div class="form-group mb-1">
            <label for="projectmonthstart" class='mb-0'>Search by Date / Date Range</label>
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
        <!-- Search By Client Area Starts -->
        <div class="input-group input-group-sm mb-1">
            <label for="projectclient" class='mb-0'>Search by Client</label>
            <div class="input-group input-group-sm">
                <input 
                    type="text"
                    name='projectclient'
                    id='projectclient'
                    class="ploinput form-control"
                    placeholder="Search Client"
                    onkeyup="ajaxFunction('clientSearch', this, '')">
            </div>
            <span class="invalid-feedback" role="alert" id="projectclient_error_span">
                <strong id="projectclient_error"></strong>
            </span>
        </div>
        <!-- Search By Client Area Ends -->
        <!-- Search By User Area Starts -->
        <div class="input-group input-group-sm">
            <label for="projectclient" class='mb-0'>Search by Project Owner:</label>
            <div class="input-group input-group-sm">
                <input
                    type="text"
                    name='projectmanager'
                    id='projectmanager'
                    class="ploinput form-control"
                    placeholder="Search User">
            </div>
            <span class="invalid-feedback" role="alert" id="projectmanager_error_span">
                <strong id="projectmanager_error"></strong>
            </span>
        </div>
        <!-- Search By User Area Ends -->
        <button class="btn btn-success btn-block btn-sm mt-2" type="submit" id="button-addon2">Go</button>
    </form>
</div>