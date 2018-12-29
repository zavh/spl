                                        <!-- Basic Salary Input Starts -->
                                        <div class="form-group row">
                                            <div class="input-group input-group-sm col-md-12" >
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="font-size:12px;width:150px">Basic Salary</span>
                                                </div>
                                                <input id="basic" type="number" class="form-control" name="basic" value="{{ old('basic') }}" required>
                                            </div>
                                        </div>
                                        <!-- Basic Salary Input Ends -->
                                        <!-- Joining Date Input Starts -->
                                        <div class="form-group row">
                                            <div class="input-group input-group-sm col-md-12" style='margin-top:-10px'>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="font-size:12px;width:150px">Joining Date</span>
                                                </div>
                                                <input id="joindate" type="date" class="form-control" name="joindate" value="{{ old('joindate') }}" required>
                                            </div>
                                        </div>
                                        <!-- Joining Date Input Ends -->

                                        <!-- DOB Input Starts -->
                                        <div class="form-group row">
                                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="font-size:12px;width:150px">Date of Birth</span>
                                                </div>
                                                <input id="dob" type="date" class="form-control" name="dob" value="{{ old('dob') }}"  max="<?php echo date('Y-m-d');?>" required>
                                            </div>
                                        </div>
                                        <!-- DOB Input Ends -->

                                        <!-- Gender Input Starts -->
                                        <div class="form-group row" style='margin-top:-10px'>
                                                <div class="input-group input-group-sm col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="font-size:12px;width:150px">Gender</span>
                                                    </div>
                                                <select name="gender" id="gender" class="form-control" required>
                                                <option value="" disabled>Select One</option>
                                                <option value="m" >Male</option>
                                                <option value="f" >Female</option>
                                                <option value="o" >Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Gender Input End -->
                                        <!-- Pay Media Input Starts -->
                                        <div class="form-group row" style='margin-top:-10px'>
                                                <div class="input-group input-group-sm col-md-12">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="font-size:12px;width:150px">Pay Out Mode</span>
                                                    </div>
                                                <select name="paymode" id="paymode" class="form-control" required>
                                                <option value="c" >Cash</option>
                                                <option value="b" >Bank</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Pay Media Input End -->
                                        <!-- Account Name Input Starts -->
                                        <div class="form-group row">
                                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="font-size:12px;width:150px">Account Name</span>
                                                </div>
                                                <input id="dob" type="text" class="form-control" name="dob" value="{{ old('dob') }}"  max="<?php echo date('Y-m-d');?>" required>
                                            </div>
                                        </div>
                                        <!-- Account Name Input Ends -->
                                        <!-- Account Number Input Starts -->
                                        <div class="form-group row">
                                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="font-size:12px;width:150px">Account Number</span>
                                                </div>
                                                <input id="dob" type="text" class="form-control" name="dob" value="{{ old('dob') }}"  max="<?php echo date('Y-m-d');?>" required>
                                            </div>
                                        </div>
                                        <!-- Account Number Input Ends -->
                                        <!-- Bank Name Input Starts -->
                                        <div class="form-group row">
                                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="font-size:12px;width:150px">Bank Name</span>
                                                </div>
                                                <input id="dob" type="text" class="form-control" name="dob" value="{{ old('dob') }}"  max="<?php echo date('Y-m-d');?>" required>
                                            </div>
                                        </div>
                                        <!-- Bank Name Input Ends -->
                                        <!-- Branch Input Starts -->
                                        <div class="form-group row">
                                            <div class="input-group input-group-sm col-md-12"  style='margin-top:-10px'>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="font-size:12px;width:150px">Branch</span>
                                                </div>
                                                <input id="dob" type="text" class="form-control" name="dob" value="{{ old('dob') }}"  max="<?php echo date('Y-m-d');?>" required>
                                            </div>
                                        </div>
                                        <!-- Branch Input Ends -->