<!-- Name Input Starts -->
<div class="form-group row">
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="font-size:12px;width:150px">Username</span>
        </div>
        <input id="name" type="text" class="form-control useraccount" name="name" value="{{ $user->name }}" required autofocus>
        <span class="invalid-feedback" role="alert" id="name_error_span">
            <strong id="name_error"></strong>
        </span>
    </div>
</div>
<!-- Name Input Ends -->
<!-- First Name Input Starts -->
<div class="form-group row" style='margin-top:-10px'>
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="font-size:12px;width:150px">First Name</span>
        </div>
        <input id="fname" type="text" class="form-control useraccount" name="fname" value="{{ $user->fname }}" required autofocus>
        <span class="invalid-feedback" role="alert" id="fname_error_span">
            <strong id="fname_error"></strong>
        </span>
    </div>
</div>
<!-- First Name Input Ends -->
<!-- Surname Input Starts -->
<div class="form-group row" style='margin-top:-10px'>
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="font-size:12px;width:150px">Surname</span>
        </div>
        <input id="sname" type="text" class="form-control useraccount" name="sname" value="{{ $user->sname }}" required autofocus>
        <span class="invalid-feedback" role="alert" id="sname_error_span">
            <strong id="sname_error"></strong>
        </span>
    </div>
</div>
<!-- Surname Input Ends -->
<!-- Phone Input Starts -->
<div class="form-group row" style='margin-top:-10px'>
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="font-size:12px;width:150px">Phone Number</span>
        </div>
        <input id="phone" type="text" class="form-control useraccount" name="phone" value="{{ $user->phone }}" required autofocus>
        <span class="invalid-feedback" role="alert" id="phone_error_span">
            <strong id="phone_error"></strong>
        </span>
    </div>
</div>
<!-- Phone Input Ends -->
<!-- Address Input Starts -->
<div class="form-group row" style='margin-top:-10px'>
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="font-size:12px;width:150px">Address</span>
        </div>
        <input id="address" type="text" class="form-control useraccount" name="address" value="{{ $user->address }}" required autofocus>
        <span class="invalid-feedback" role="alert" id="address_error_span">
            <strong id="address_error"></strong>
        </span>
    </div>
</div>
<!-- Address Input Ends -->
<!-- Email Input Starts -->
<div class="form-group row" style='margin-top:-10px'>
        <div class="input-group input-group-sm col-md-12">
            <div class="input-group-prepend">
                <span class="input-group-text" style="font-size:12px;width:150px">Email</span>
            </div>
        <input id="email" type="email" class="form-control useraccount" name="email" value="{{ $user->email }}" required>
        <span class="invalid-feedback" role="alert" id="email_error_span">
            <strong id="email_error"></strong>
        </span>
    </div>
</div>
<!-- Email Input Ends -->
<!-- Department Input Starts -->
<div class="form-group row" style='margin-top:-10px'>
        <div class="input-group input-group-sm col-md-12">
            <div class="input-group-prepend">
                <span class="input-group-text" style="font-size:12px;width:150px">Department</span>
            </div>
        <select name="department" id="department" class="form-control useraccount" required {{ Auth::User()->role_id != 1?'disabled':''}}>
        <option value="" disabled>Select One</option>
        @foreach($departments as $department)
            <option value="{{$department->id}}" {{$department->id == $user->department_id ? 'selected' : ''}}>{{$department->name}}</option>
        @endforeach
        </select>
    </div>
</div>
<!-- Department Input Ends -->
<!-- Designation Input Starts -->
<div class="form-group row" style='margin-top:-10px'>
        <div class="input-group input-group-sm col-md-12">
            <div class="input-group-prepend">
                <span class="input-group-text" style="font-size:12px;width:150px">Designation</span>
            </div>
        <select name="designation" id="designation" class="form-control useraccount" required {{ Auth::User()->role_id != 1?'disabled':''}}>
        <option value="" disabled>Select One</option>
        @foreach($designations as $designation)
            <option value="{{$designation->id}}" {{$designation->id == $user->designation_id ? 'selected' : ''}}>{{$designation->name}}</option>
        @endforeach
        </select>
    </div>
</div>
<!-- Designation Input Ends -->
<!-- Role Input Starts -->
<div class="form-group row" style='margin-top:-10px'>
        <div class="input-group input-group-sm col-md-12">
            <div class="input-group-prepend">
                <span class="input-group-text" style="font-size:12px;width:150px">Role</span>
            </div>
        <select name="role" id="role" class="form-control useraccount" required {{ Auth::User()->role_id != 1?'disabled':''}}>
        <option value="" disabled>Select One</option>
        @foreach($roles as $role)
            <option value="{{$role->id}}" {{$role->id == $user->role_id ? 'selected' : ''}}>{{$role->role_name}}</option>
        @endforeach
        </select>
    </div>
</div>
<!-- Role Input Ends -->
<!--Salary structure Input starts-->
<!--////////////////////////////////////////////-->
{{-- @if(Auth::User()->role_id == 1) --}}
<!--////////////////////////////////////////////-->
<div class="form-group row" style='margin-top:-10px'>
    <div class="input-group input-group-sm col-md-12">
        <div class="input-group-prepend">
            <span class="input-group-text" style="font-size:12px;width:150px">Salary Structure</span>
        </div>
        <select name="salarystructure" id="salarystructure" class="form-control useraccount" required {{ Auth::User()->role_id != 1?'disabled':''}}>
        <option value="" disabled>Select One</option>
        @foreach($salarystructures as $salarystructure)
            <option value="{{$salarystructure->id}}" {{$salarystructure->id == $user->salaryprofile ? 'selected' : ''}}>{{$salarystructure->structurename}}</option>
        @endforeach
        </select>
    </div>
</div>
<!--////////////////////////////////////////////-->
{{-- @endif --}}
<!--////////////////////////////////////////////-->
<!--Salary structure Input ends-->
<!--Active Status Input starts-->
<div class="form-group row" style='margin-top:-10px'>
        <div class="input-group input-group-sm col-md-12">
            <div class="input-group-prepend">
                <span class="input-group-text" style="font-size:12px;width:150px">Active Status</span>
            </div>
        <select name="active_status" id="active_status" class="form-control useraccount" required {{ Auth::User()->role_id != 1?'disabled':''}}>
        <option value="" disabled>Select One</option>
        <option value="1" {{$user->active == "1" ? 'selected' : ''}}>active</option>
        <option value="0" {{$user->active == "0" ? 'selected' : ''}}>disabled</option>
        </select>
    </div>
</div>
<!--Active Status Input ends-->