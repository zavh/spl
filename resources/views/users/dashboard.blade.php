<div class="container-fluid">
  <div class="card-deck mb-3 text-center">
      <!-- Incomplete reports start -->
      <div class="card mb-4 shadow-sm" id='user-reports'>
        <script>
          ajaxFunction('viewuserreports', '' , 'user-reports');
        </script>
      </div>
      <!-- Incomplete reports ends -->
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
<script src="{{ asset('js/users.js?version=0.2') }}"></script>