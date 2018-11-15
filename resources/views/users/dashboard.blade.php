<div class="container-fluid">
      <div class="card-deck mb-3 text-center">
        <!-- Unllocated Project Section Starts -->
        <div class="card mb-4 shadow-sm" id='user-reports'>
          <div class="card-header text-white bg-danger" style='position:relative;font-size:12px;height:40px'>
            <span class="my-0 font-weight-normal">Unassigned Projects</span>
            <span style='position:absolute;right:10px'>
            <a href="/reports/create" style='color:white'>Create Report</a>
            </span>
          </div>
          <!-- Body -->
          <div class="card-body">
          <table style='font-size:12px;width:100%;table-layout:fixed'>
            <thead>
                <tr style='border:1px solid black'>
                    <th scope="col">Client</th>
                    <th scope="col">Enquiry</th>
                    <th scope="col">View</th>
                </tr>
            <thead>
            </table>            
          </div>
          <!-- Body -->
        </div>
        <!-- Task Section Starts -->
        <div class="card mb-4 shadow-sm" id='user-tasks'>
			<script>
				ajaxFunction('viewusertasks', '' , 'user-tasks');
			</script>
        </div>
		<!-- Task Section Ends -->
		<!-- Profile Area Starts-->
        <div class="card mb-4 shadow-sm" id='user-profile'>
			<script>
				ajaxFunction('viewuser', {{Auth::user()->id}} , 'user-profile');
			</script>
        </div>
		<!-- Profile Area Ends-->
      </div>
    </div>