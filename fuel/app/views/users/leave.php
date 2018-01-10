<?php
$msg = \Fuel\Core\Session::get_flash("msg");
$smsg = \Fuel\Core\Session::get_flash("smsg");
?>

<?php if(is_array($msg) && count($msg) > 0 ):?>
    <section class="container-fluid leave-app-form">
        <div class="row">
            <div class="col-md-12">
                <?php
                foreach ($msg as $error) {
                    echo "<div class='alert alert-danger text-center alert-msg'><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$error."</div>";
                }
                ?>
            </div>
        </div>
    </section>
<?php endif;?>

<?php if(is_array($smsg) && count($smsg) > 0 ):?>
    <section class="container-fluid leave-app-form">
        <div class="row">
            <div class="col-md-12">
                <?php
                foreach ($smsg as $error) {
                    echo "<div class='alert alert-success text-center alert-msg'><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$error."</div>";
                }
                ?>
            </div>
        </div>
    </section>
<?php endif;?>
<section class="container-fluid leave-app-form" id="leave-app-form">
    <div class="row">
        <div class="col-md-6 col-md-offset-1">
            <?php echo \Fuel\Core\Form::open(array('action'=>'user/leave_apply', 'class'=>'', 'method'=>'post', 'id'=>'sickleave_form', 'enctype' => 'multipart/form-data'))?>
                <?php echo \Fuel\Core\Form::csrf()?>
                <div class="form-group">
                    <label for="leave_cat">Leave Category</label>
                    <select name="leave_cat" id="leave_cat" class="form-control">
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Vacation Leave">Vacation Leave</option>
                        <option value="Paternity Leave">Paternity Leave</option>
                        <option value="Maternity Leave">Maternity Leave</option>
                    </select>
                </div>
                <div class="form-group startdate">
                    <label for="start_date">Start date</label>
                    <input type="text" class="form-control" id="start_date" placeholder="start date" name="start_date">
                    <span class="btn btn-default" id="startdate"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="form-group enddate">
                    <label for="end_date">End date</label>
                    <input type="text" class="form-control" id="end_date" placeholder="end date" name="end_date">
                    <span class="btn btn-default" id="enddate"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="form-group attachments">
                    <label for="attachments">Attachments</label>
                    <input type="file" id="attachments" name="attachments" />
                    <p class="help-block">Medical certificates for sick leave and etc...</p>
                </div>
                <div class="form-group reason">
                    <label for="reason">Reason</label>
                    <textarea name="reason" id="reason" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <div class="form-group submit">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            <?php echo \Fuel\Core\Form::close()?>
        </div>

        <div class="col-md-4">
            <?php if(count($pending_leave) > 0):?>
            <?php
                $leave = array_shift($pending_leave);

                $today = time();
                $leave_enddate = strtotime($leave->end_leave);
            ?>
<!--                --><?php //if($leave_enddate > $today): ?>
                    <?php
                    $status = "";
                    switch ($leave->status){
                        case "pending":
                            $status = "status-pending";
                            break;
                        case "approved":
                            $status = "status-approved";
                            break;
                        case "rejected":
                            $status = "status-rejected";
                            break;
                    }
                    ?>
                    <div class="leave clearfix">
                        <p class="leave-title"><?php echo $leave->type ?> <span class="sub-title">(most recent leave)</span></p>
                        <p class="leave-status text-right"><span class="status <?php echo $status ?>"><?php echo $leave->status?></span></p>
                        <?php if($status != "status-approved"):?>
                            <?php echo \Fuel\Core\Form::open(array('action'=>'user/leave_delete', 'method'=>'post', 'id'=>'leave_delete', 'class' => 'leave-status'))?>
                                <input type="hidden" value="<?php echo $leave->leave_id?>" name="leave_id"/>
                                <?php echo \Fuel\Core\Form::csrf();?>
                                <button type="submit" class="status cancel-leave"><i class="fa fa-remove"></i> cancel</button>
                            <?php echo \Fuel\Core\Form::close() ?>
                        <?php endif;?>
                        <p class="leave-date"><span><i class="fa fa-calendar-o"></i> From:</span> <?php echo $leave->start_leave?></p>
                        <p class="leave-date"><span><i class="fa fa-calendar-o"></i> To:</span> <?php echo $leave->end_leave?></p>
                        <?php echo "<p class='leave-reason'><span>Reason</span><span class='divider'></span><span class='reason-content'>{$leave->reason}</span></p>"; ?>
                        <?php if(!empty($leave->comments) && $leave->comments != ""):?>
                            <div class="seperate"></div>
                            <p class="leave-comment-title"><strong>Management Comments</strong></p>
                            <p class="leave-comments"><?php echo $leave->comments?></p>
                        <?php endif;?>
                    </div>
<!--                --><?php //endif; ?>
            <?php endif; ?>
            <?php if(count($leave_info) > 0):?>
                <table class="table table-responsive leave-info">
                    <thead>
                        <tr>
                            <th>Leave Category</th>
                            <th>Days Allotted</th>
                            <th>Days Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leave_info as $leave_name => $info):?>
                            <tr>
                                <td><?php echo $leave_name?></td>
                                <td><?php echo $info['days_allotted']?></td>
                                <td><?php echo $info['days_remaining']?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</section>
