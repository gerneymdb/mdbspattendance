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
            <h4><?php echo __("add a new employee")?></h4>
            <div class="add clearfix">

                <form action="<?php echo $base_url .'administrator/add_employee' ?>" class="add-employee" method="post" >
                    <?php echo \Fuel\Core\Form::csrf() ?>
                    <div class="employee-fullname clearfix">
                        <!-- emp fullname -->
                        <div class="form-group">
                            <label for="firstname"><?php echo __("first name") ?></label>
                            <input type="text" class="form-control" id="firstname" placeholder="<?php echo __("first name") ?>" name="fname"/>
                        </div>
                        <div class="form-group">
                            <label for="middlename"><?php echo __("middle name") ?></label>
                            <input type="text" class="form-control" id="middlename" placeholder="<?php echo __("middle name") ?>" name="mname"/>
                        </div>
                        <div class="form-group">
                            <label for="lastname"><?php echo __("last name") ?></label>
                            <input type="text" class="form-control" id="lastname" placeholder="<?php echo __("last name") ?>" name="lname" />
                        </div>
                        <div class="form-group">
                            <label for="c_status"><?php echo __("civil status") ?></label>
                            <select class="form-control" name="civil_status" id="c_status">
                                <option value="single"><?php echo __("single") ?></option>
                                <option value="married"><?php echo __("married") ?></option>
                                <option value="widow"><?php echo __("widow") ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bdate"><?php echo __("birthday") ?></label>
                            <input type="text" name="birthdate" id="bdate" placeholder="<?php echo __("birthdate") ?>"  class="form-control"/>
                        </div>
                        <!-- /fullname -->
                    </div>

                    <div class="emp-company-detail">
                        <!-- work related -->
                        <div class="form-group">
                           <label for="shift_id"><?php echo __("shift") ?></label>
                           <select name="shift_id" class="form-control" id="shift_id">
                              <?php if(count($shifts) > 0):?>
                                  <?php foreach ($shifts as $shift):?>
                                      <option value="<?php echo $shift->shift_id?>"><?php echo $shift->shift_name?></option>
                                  <?php endforeach;?>
                              <?php endif;?>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="userid"><?php echo __("user's id") ?></label>
                           <input type="text" class="form-control" id="userid" placeholder="<?php echo __("user's id") ?>" name="userid" />
                        </div>
                        <div class="form-group">
                           <label for="co_position"><?php echo __("company position") ?></label>
                           <input type="text" class="form-control" id="co_position" placeholder="<?php echo __("his/her position in the company") ?>" name="co_position" />
                        </div>
                        <div class="form-group">
                            <label for="emailaddress"><?php echo __("email address") ?></label>
                            <input type="email" class="form-control" id="emailaddress" placeholder="<?php echo __("email address") ?>" name="email" />
                        </div>
                        <div class="form-group">
                           <button class="btn btn-default pull-right" type="submit"><i class="fa fa-save"></i> <?php echo __("add") ?></button>
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
            <h4><?php echo __("list of employees") ?></h4>
            <?php if(count($employees) > 0):?>
               <?php foreach ($employees as $employee):?>
                    <?php $this_emp = $user_creds[$employee->userid]?>
                    <div class="employee-info clearfix" id="<?php echo $employee->userid; ?>">
                        <div class="new_pwd_container hide">
                            <span class="hide_new_pwd"><i class="fa fa-close"></i></span>
                            <p class="new_pwd_title text-center text-danger"><?php echo __("your new password") ?></p>
                            <p class="new_pwd text-center"></p>
                        </div>
                        <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> <?php echo __("processing") ?></span></div>
                        <div class="buttons">
                            <button type="button" class="btn btn-default emp_edit" data-userid="<?php echo $employee->userid?>" data-employeeid="<?php echo $employee->employee_id?>">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </button>
                            <button type="button" class="btn btn-default emp_delete" data-userid="<?php echo $employee->userid?>" data-employeeid="<?php echo $employee->employee_id?>">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="record clearfix">
                            <div class="info">
                                <p class="emp_fullname">
                                    <span class="fname"><?php echo ucwords($employee->fname)?></span>
                                    <span class="mname"><?php echo ucwords($employee->mname)?></span>
                                    <span class="lname"><?php echo ucwords($employee->lname)?></span>
                                </p>
                                <p class="userid"><?php echo __("user id") ?>: <?php echo $employee->userid?></p>
                                <p class="civil_status"><?php echo __("civil status") ?>: <span class="cstatus"><?php echo $employee->civil_status?></span></p>
                                <p class="birthdate"><?php echo __("birthdate")?>: <span class="birth_date"><?php echo $employee->birthdate?></span></p>
                            </div>
                            <div class="work-schedule">
                                <p class="sched" data-shift-id="<?php echo $employee->shift_id ?>"><?php echo __("shift") ?>: <span class="shift-name"><?php echo $shifts[$employee->shift_id]->shift_name ?></span></p>
                                <p class="co_position"><?php echo __("company position") ?>: <span class="position"><?php echo $employee->co_position?></span></p>
                                <p class="emp_email"><?php echo __("email address") ?>: <span class="email"><?php echo $this_emp->email?></span></p>
                                <form action="#" class="reset_pwd">
                                    <input type="hidden" name="username" value="<?php echo $employee->userid ?>">
                                    <button type="button" class="btn btn-default btn_rst_pwd" data-url="<?php echo $base_url .'ajaxcall/reset_password'?>">
                                        <i class="fa fa-key"></i> <?php echo __("reset password") ?>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="activity">
                            <div class="additions">
                                <p><?php echo __("created") ?>: <span class="created_at"><?php  echo ($this_emp->created_at > 0)? strftime("%B %d, %Y %H:%M:%S",  $this_emp->created_at): "not yet" ?></span></p>
                                <p><?php echo __("last update") ?>: <span class="last_update"><?php echo ($this_emp->updated_at > 0)? strftime("%B %d, %Y %H:%M:%S",  $this_emp->updated_at): "not yet" ?></span></p>
                            </div>

                            <div class="changes">
                                <p><?php echo __("last password change") ?>:
                                    <span class="last_pwd_change">
                                        <?php echo ($this_emp->time_last_pwd_change > 0)? strftime("%B %d, %Y %H:%M:%S", $this_emp->time_last_pwd_change) : "not yet" ?>
                                    </span>
                                </p>
                                <p><?php echo __("last log-in") ?>:
                                    <span class="last_login">
                                        <?php echo ($this_emp->last_login > 0)? strftime("%B %d, %Y %H:%M:%S", $this_emp->last_login): "not yet" ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
               <?php endforeach;?>

            <?php else:?>

                <div class="alert alert-info">
                    <p class="text-center"><i class='glyphicon glyphicon-exclamation-sign'></i> <strong><?php echo __("no record found") ?></strong></p>
                </div>

            <?php endif;?>

        </div>
    </div>
</section>


<!-- Edit -->
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> <?php echo __("processing") ?></span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __("edit information") ?></h4>
            </div>
            <div class="modal-body">
                <form action="#" id="edit_employee">
                    <input type="hidden" value="<?php echo $base_url .'ajaxcall/save_employee_info'?>" id="emp_edit_url" />
                    <input type="hidden" value="" name="userid" id="user_id" />
                    <input type="hidden" value="" name="employee_id" id="employeeid" />
                    <div id="csrftoken">
                        <?php echo \Fuel\Core\Form::csrf();?>
                    </div>
                    <div class="form-group">
                        <label for="first_name"><?php echo __("first name") ?></label>
                        <input type="text" class="form-control" id="first_name" placeholder="<?php echo __("first name") ?>" name="fname" value="" />
                    </div>
                    <div class="form-group">
                        <label for="middle_name"><?php echo __("middle name") ?></label>
                        <input type="text" class="form-control" id="middle_name" placeholder="<?php echo __("middle name") ?>" name="mname" value="" />
                    </div>
                    <div class="form-group">
                        <label for="last_name"><?php echo __("last name") ?></label>
                        <input type="text" class="form-control" id="last_name" placeholder="<?php echo __("last name") ?>" name="lname" value="" />
                    </div>
                    <div class="form-group">
                        <label for="email"><?php echo __("email address") ?></label>
                        <input type="email" class="form-control" id="email_address" placeholder="<?php echo __("email address") ?>" name="email" value="" />
                    </div>
                    <div class="form-group">
                        <label for="bday"><?php echo __("birthdate") ?></label>
                        <input type="text" class="form-control" id="bday" placeholder="<?php echo __("birthdate") ?>" name="birthdate" value="" />
                    </div>
                    <div class="form-group">
                        <label for="cstatus"><?php echo __("civil status") ?></label>
                        <select name="civil_status" id="cstatus" class="form-control">
                            <option value="single"><?php echo __("single") ?></option>
                            <option value="married"><?php echo __("married") ?></option>
                            <option value="widow"><?php echo __("widow") ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shift"><?php echo __("shift") ?></label>
                        <select name="shift_id" id="shift" class="form-control">
                            <?php if(count($shifts) > 0):?>
                                <?php foreach ($shifts as $shift):?>
                                    <option value="<?php echo $shift->shift_id?>"><?php echo $shift->shift_name?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="c_position"><?php echo __("company position") ?></label>
                        <input type="text" class="form-control" id="c_position" placeholder="<?php echo __("company position") ?>" name="co_position" value="" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("cancel") ?></button>
                <button type="button" class="btn btn-primary" id="save_emp_info"><i class="fa fa-save"></i> <?php echo __("save") ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> <?php echo __("processing") ?></span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info" class="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __("are you sure that you want to delete this this employee's record?") ?></h4>
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
                    <p class="text-center"><i class="fa fa-warning"></i> <strong><?php echo __("warning") ?>:</strong> <?php echo __("all records of this employee will also be deleted.") ?>.</p>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("no") ?></button>
                <button type="button" class="btn btn-primary" id="deletebtn"><?php echo __("yes") ?></button>
            </div>
        </div>
    </div>
</div>