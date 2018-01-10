<?php  $base_url = \Fuel\Core\Config::get("base_url"); ?>
<?php
$msg = \Fuel\Core\Session::get_flash("msg");
$smsg = \Fuel\Core\Session::get_flash("smsg");
?>

<?php if(is_array($msg) && count($msg) > 0 ):?>
    <section class="container-fluid employees_error_msg">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <?php
                foreach ($msg as $error) {
                    echo "<div class=\"alert alert-danger text-center\"><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$error."</div>";
                }
                ?>
            </div>
        </div>
    </section>

<?php endif;?>

<?php if(is_array($smsg) && count($smsg) > 0 ):?>
    <section class="container-fluid employees_error_msg">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <?php
                foreach ($smsg as $error) {
                    echo "<div class=\"alert alert-success text-center\"><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$error."</div>";
                }
                ?>
            </div>
        </div>
    </section>

<?php endif;?>
<section class="container-fluid employees clearfix">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h4>Add a new employee</h4>
            <div class="add clearfix">

                <form action="<?php echo $base_url .'administrator/add_employee' ?>" class="add-employee" method="post" >
                    <?php echo \Fuel\Core\Form::csrf() ?>
                    <div class="employee-fullname clearfix">
                        <!-- emp fullname -->
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" placeholder="First Name" name="fname"/>
                        </div>
                        <div class="form-group">
                            <label for="middlename">Middle Name</label>
                            <input type="text" class="form-control" id="middlename" placeholder="Middle Name" name="mname"/>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" placeholder="Last Name" name="lname" />
                        </div>
                        <!-- /fullname -->
                    </div>

                    <div class="emp-company-detail">
                        <!-- work related -->
                        <div class="form-group">
                           <label for="shift_id">Shift</label>
                           <select name="shift_id" class="form-control" id="shift_id">
                              <?php if(count($shifts) > 0):?>
                                  <?php foreach ($shifts as $shift):?>
                                      <option value="<?php echo $shift->shift_id?>"><?php echo $shift->shift_name?></option>
                                  <?php endforeach;?>
                              <?php endif;?>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="userid">User's ID</label>
                           <input type="text" class="form-control" id="userid" placeholder="User ID" name="userid" />
                        </div>
                        <div class="form-group">
                           <label for="emailaddress">Email address</label>
                           <input type="email" class="form-control" id="emailaddress" placeholder="Email address" name="email" />
                        </div>
                        <div class="form-group">
                           <button class="btn btn-default pull-right" type="submit"><i class="fa fa-save"></i> Add</button>
                        </div>
                       <!-- /work related -->
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>

<section class="container-fluid employees">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <hr>
            <h4>List of employees</h4>
            <?php if(count($employees) > 0):?>
               <?php foreach ($employees as $employee):?>
                    <?php $this_emp = $user_creds[$employee->userid]?>
                    <div class="employee-info clearfix" id="<?php echo $employee->userid; ?>">
                        <div class="new_pwd_container hide">
                            <span class="hide_new_pwd"><i class="fa fa-close"></i></span>
                            <p class="new_pwd_title text-center text-danger">your new password</p>
                            <p class="new_pwd text-center"></p>
                        </div>
                        <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> processing</span></div>
                        <div class="buttons">
                            <button type="button" class="btn btn-default emp_edit" data-userid="<?php echo $employee->userid?>" data-employeeid="<?php echo $employee->employee_id?>">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </button>
                            <button type="button" class="btn btn-default emp_delete" data-userid="<?php echo $employee->userid?>" data-employeeid="<?php echo $employee->employee_id?>">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="record">
                            <div class="info">
                                <p class="emp_fullname">
                                    <span class="fname"><?php echo ucwords($employee->fname)?></span>
                                    <span class="mname"><?php echo ucwords($employee->mname)?></span>
                                    <span class="lname"><?php echo ucwords($employee->lname)?></span>
                                </p>
                                <p>ID: <?php echo $employee->userid?></p>
                                <p class="emp_email">EMAIL: <span class="email"><?php echo $this_emp->email?></span></p>
                            </div>
                            <div class="work-schedule">
                                <p class="sched" data-shift-id="<?php echo $employee->shift_id ?>">Shift: <span class="shift-name"><?php echo $shifts[$employee->shift_id]->shift_name ?></span></p>
                                <form action="#" class="reset_pwd">
                                    <input type="hidden" name="username" value="<?php echo $employee->userid ?>">
                                    <button type="button" class="btn btn-default btn_rst_pwd" data-url="<?php echo $base_url .'ajaxcall/reset_password'?>">
                                        <i class="fa fa-key"></i> Reset Password
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="activity">
                            <div class="additions">
                                <p>Created: <?php  echo ($this_emp->created_at > 0)? strftime("%B %d, %Y %H:%M:%S",  $this_emp->created_at): "not yet" ?></p>
                                <p>Last Update: <?php echo ($this_emp->updated_at > 0)? strftime("%B %d, %Y %H:%M:%S",  $this_emp->updated_at): "not yet" ?></p>
                            </div>

                            <div class="changes">
                                <p>Last password change: <?php echo ($this_emp->time_last_pwd_change > 0)? strftime("%B %d, %Y %H:%M:%S", $this_emp->time_last_pwd_change) : "not yet" ?></p>
                                <p>Last log-in: <?php echo ($this_emp->last_login > 0)? strftime("%B %d, %Y %H:%M:%S", $this_emp->last_login): "not yet" ?></p>
                            </div>
                        </div>
                    </div>
               <?php endforeach;?>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- Edit -->
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> processing</span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Information</h4>
            </div>
            <div class="modal-body">
                <form action="#" id="edit_employee">
                    <?php echo \Fuel\Core\Form::csrf() ?>
                    <input type="hidden" value="<?php echo $base_url .'ajaxcall/save_employee_info'?>" id="emp_edit_url" />
                    <input type="hidden" value="" name="userid" id="user_id" />
                    <input type="hidden" value="" name="employee_id" id="employeeid" />
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" placeholder="First Name" name="fname" value="" />
                    </div>
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" placeholder="Middle Name" name="mname" value="" />
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="lname" value="" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email_address" placeholder="Email" name="email" value="" />
                    </div>
                    <div class="form-group">
                        <label for="shift">Shift</label>
                        <select name="shift_id" id="shift" class="form-control">
                            <?php if(count($shifts) > 0):?>
                                <?php foreach ($shifts as $shift):?>
                                    <option value="<?php echo $shift->shift_id?>"><?php echo $shift->shift_name?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save_emp_info"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> processing</span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info" class="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Are you sure that you want to delete this this employee's record?</h4>
            </div>
            <div class="modal-body">
                <p class="delete_emp_name"><i class="fa fa-user-circle-o"></i> <span class="name"></span></p>
                <form action="#" id="deleteform">
                    <input type="hidden" value="<?php echo $base_url .'ajaxcall/delete_employee_record'?>" id="emp_delete_url" />
                    <input type="hidden" name="userid" value="" id="delete_userid" />
                </form>
            </div>
            <div class="modal-footer">
                <div class="alert alert-info">
                    <p class="text-center"><i class="fa fa-warning"></i> <strong>Warning:</strong> All records of this employee will also be deleted.</p>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="deletebtn">Yes</button>
            </div>
        </div>
    </div>
</div>