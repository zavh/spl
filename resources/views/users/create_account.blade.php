                                        <!-- Name Input Starts -->
                                        <div class="form-group row">
                                            <div class="input-group input-group-sm col-md-12">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="font-size:12px;width:150px">Username</span>
                                                </div>
                                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Name Input Ends -->
                                        <!-- Email Input Starts -->
                                        <div class="form-group row" style='margin-top:-10px'>
                                                <div class="input-group input-group-sm col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="font-size:12px;width:150px">Email</span>
                                                    </div>
                                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Email Input Ends -->
                                        <!-- Password Input Starts -->
                                        <div class="form-group row" style='margin-top:-10px'>
                                                <div class="input-group input-group-sm col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="font-size:12px;width:150px">Password</span>
                                                    </div>
                                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Password Input Ends -->
                                        <!-- Confirm Password Input Starts -->
                                        <div class="form-group row" style='margin-top:-10px'>
                                                <div class="input-group input-group-sm col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="font-size:12px;width:150px">Confirm Password</span>
                                                    </div>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                            </div>
                                        </div>
                                        <!-- Confirm Password Input Ends -->
                                        <!-- Department Input Starts -->
                                        <div class="form-group row" style='margin-top:-10px'>
                                                <div class="input-group input-group-sm col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="font-size:12px;width:150px">Department</span>
                                                    </div>
                                                <select name="department" id="department" class="form-control" required>
                                                <option value="" disabled>Select One</option>
                                                @foreach($departments as $department)
                                                    <option value="{{$department->id}}">{{$department->name}}</option>
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
                                                <select name="designation" id="designation" class="form-control" required>
                                                <option value="" disabled>Select One</option>
                                                @foreach($designations as $designation)
                                                    <option value="{{$designation->id}}">{{$designation->name}}</option>
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
                                                <select name="role" id="role" class="form-control" required>
                                                <option value="" disabled>Select One</option>
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->role_name}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Role Input Ends -->
                                        <!--Salary structure Input starts-->
                                        <div class="form-group row" style='margin-top:-10px'>
                                            <div class="input-group input-group-sm col-md-12">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="font-size:12px;width:150px">Salary Structure</span>
                                                </div>
                                                <select name="salarystructure" id="salarystructure" class="form-control" required>
                                                <option value="" disabled>Select One</option>
                                                @foreach($salarystructures as $salarystructure)
                                                    <option value="{{$salarystructure->id}}">{{$salarystructure->structurename}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--Salary structure Input ends-->