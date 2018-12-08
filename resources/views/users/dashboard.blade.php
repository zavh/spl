<div class="container-fluid">
  <div class='row'>
    <!-- Incomplete reports start -->
    <div class="col-md-4">
      <div class="shadow-sm border" id='user-reports'>
        <script>
          ajaxFunction('viewuserreports', '' , 'user-reports');
        </script>
      </div>
    </div>
    <!-- Incomplete reports ends -->
    <!-- Task Section Starts -->
    <div class="col-md-4" >
      <div class="shadow-sm border" id='user-tasks'>
        <script>
          ajaxFunction('viewusertasks', '' , 'user-tasks');
        </script>
      </div>
    </div>
    <!-- Task Section Ends -->
    <!-- Profile Area Starts-->
    <div class="col-md-4">
      <div class="shadow-sm border"  id='user-profile'>
        <script>
          ajaxFunction('viewuser', {{Auth::user()->id}} , 'user-profile');
        </script>
      </div>
    </div>
    <!-- Profile Area Ends-->
  </div>
</div>
<script src="{{ asset('js/users.js?version=0.3') }}"></script>