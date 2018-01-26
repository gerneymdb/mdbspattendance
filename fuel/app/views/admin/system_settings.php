<?php  $base_url = \Fuel\Core\Config::get("base_url"); ?>
<section class="container-fluid system_settings">
    <div class="row">
        <div class="col-md-12">
            <h3><?php echo __("system settings")?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php
                $msg = \Fuel\Core\Session::get_flash("setting_f_msg");
                $smsg = \Fuel\Core\Session::get_flash("setting_s_msg");
            ?>

            <?php if(is_array($msg) && count($msg) > 0 ):?>

                <?php
                    foreach ($msg as $error) {
                        echo "<div class=\"alert alert-danger text-center\"><p class='text-center'><i class=\"fa fa-warning\"></i>  ".$error."</p></div>";
                    }
                ?>

            <?php endif;?>

            <?php if(is_array($smsg) && count($smsg) > 0 ):?>

                <?php
                    foreach ($smsg as $error) {
                        echo "<div class=\"alert alert-success text-center\"><p class='text-center'><i class=\"fa fa-warning\"></i>  ".$error."</p></div>";
                    }
                ?>

            <?php endif;?>


            <form action="<?php echo $base_url . 'administrator/edit_data_settings'?>" class="form_system_setting clearfix" method="post" >
                <input type="hidden" value="<?php echo $default_setting->id ?>" name="id" />
                <?php echo \Fuel\Core\Form::csrf(); ?>
                <div class="form-group default-password">
                    <label for=""><?php echo __('set default password to') ?>:</label>
                    <input type="text" class="form-control" name="default_pwd" placeholder="<?php echo __("default password")?>" value="<?php echo $default_setting->default_pwd ?>"/>
                </div>

                <p class="input-label"><?php echo  __("reset password after") ?>:</p>
                <div class="input-group">
                    <input type="number" class="form-control" name="reset_pwd_after" placeholder="<?php echo __("the number of days before password reset")?>" aria-describedby="basic-addon2"
                           value="<?php echo (ceil($default_setting->reset_pwd_after/84600))?>"/>
                    <span class="input-group-addon" id="basic-addon2"><?php echo __("days")?></span>
                </div>

                <p class="input-label"><?php echo __("session time out after") ?>:</p>
                <div class="input-group">
                    <input type="number" class="form-control" name="session_timeout_after" placeholder="<?php echo __("session time out after a number of hours")?>" aria-describedby="basic-addon2"
                        value="<?php echo (ceil($default_setting->session_timeout_after/3600)) ?>"/>
                    <span class="input-group-addon" id="basic-addon2"><?php echo __("hours")?></span>
                </div>

                <button type="submit" class="btn btn-default" id="save">
                    <i class="fa fa-save"></i>
                    <?php echo __("saved")?>
                </button>
            </form>
        </div>

        <div class="col-md-6">

            <?php
                $msg = \Fuel\Core\Session::get_flash("msg");
                $smsg = \Fuel\Core\Session::get_flash("smsg");
            ?>

            <?php if(is_array($msg) && count($msg) > 0 ):?>

                <?php
                foreach ($msg as $error) {
                    echo "<div class=\"alert alert-danger text-center\"><p class='text-center'><i class=\"fa fa-warning\"></i>  ".$error."</p></div>";
                }
                ?>

            <?php endif;?>

            <?php if(is_array($smsg) && count($smsg) > 0 ):?>

                <?php
                foreach ($smsg as $error) {
                    echo "<div class=\"alert alert-success text-center\"><p class='text-center'><i class=\"fa fa-warning\"></i>  ".$error."</p></div>";
                }
                ?>

            <?php endif;?>

            <div class="add-new-category clearfix">
                <p><strong><?php echo __("add new leave category")?></strong></p>
                <form action="<?php echo $base_url . 'administrator/add_leave_category'?>" class="form_system_setting" method="post">
                    <?php echo \Fuel\Core\Form::csrf() ?>
                    <div class="form-group">
                        <label for="leavename"><?php echo __("leave name")?></label>
                        <input type="text" name="leave_name" placeholder="<?php echo __("indicate name of new leave category")?>" id="leavename" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="days_alloted"><?php echo __("days allotted") ?></label>
                        <input type="number" name="days_alloted" placeholder="<?php echo __("number of days allotted for this leave") ?>" id="days_alloted" class="form-control" />
                    </div>
                    <button class="btn btn-default pull-right">
                        <i class="fa fa-plus-square-o"></i> <?php echo __("add") ?>
                    </button>
                </form>
            </div>

            <div class="category-leave-list">
                <table class="table table-responsive">
                    <caption><strong><?php echo __("leave category list") ?></strong></caption>
                    <thead>
                        <tr>
                            <th><?php echo __("leave name") ?></th>
                            <th><?php echo __("days allotted") ?></th>
                            <th><?php echo __("actions") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($leave_settings) > 0):?>

                            <?php foreach ($leave_settings as $leave):?>
                                <tr id="<?php echo $leave->leave_settings_id?>">
                                    <td class="leave_cat_name"><?php echo $leave->leave_name ?></td>
                                    <td class="leave_cat_days"><?php echo $leave->days_alloted ?></td>
                                    <td>
                                        <button class="btn btn-default edit-leave-cat"
                                            data-leave-id="<?php echo $leave->leave_settings_id?>"
                                            data-leave-name="<?php echo $leave->leave_name?>"
                                            data-days-alloted="<?php echo $leave->days_alloted?>"
                                        >
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>
                                        <button class="btn btn-default delete-leave-cat" data-leave-id="<?php echo $leave->leave_settings_id?>" data-leave-name="<?php echo $leave->leave_name ?>">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach;?>

                        <?php else:?>

                            <tr>
                                <td colspan="2">
                                    <div class="alert alert-info">
                                        <i class="fa fa-warning"></i> No leave category record found..
                                    </div>
                                </td>
                            </tr>

                        <?php endif;?>
                    </tbody>
                </table>
            </div>
            
        </div>

    </div>
</section>

<!-- Edit Modal -->
<div class="modal fade" id="edit_leave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> <?php echo __("processing") ?></span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info" class="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __("edit leave category information") ?></h4>
            </div>
            <div class="modal-body">
                <form action="#" id="leave_cat_form">
                    <input type="hidden" id="leave-cat-set-url" value="<?php echo $base_url . 'ajaxcall/update_leavecat'?>" />
                    <input type="hidden" name="leave_setting_id" id="lv_sett_id" value=""/>
                    <?php echo \Fuel\Core\Form::csrf() ?>
                    <div class="form-group">
                        <label for="leave_name"><?php echo __("leave name") ?></label>
                        <input type="text" name="leave_name" id="leave_name" placeholder="<?php echo __("leave category name")?>" value=""  class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="daysalloted"><?php echo __("days allotted") ?></label>
                        <input type="number" name="days_alloted" id="daysalloted" placeholder="<?php echo __("number of days allotted for this leave") ?>" value="" class="form-control"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("no")?></button>
                <button type="button" class="btn btn-primary" id="save_changes"><i class="fa fa-save"></i> <?php echo __("save changes")?></button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete_leave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> <?php echo __("processing") ?></span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info" class="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __("delete leave category") ?></h4>
            </div>
            <div class="modal-body">
                <form action="#" id="dlte_lve_form">
                    <input type="hidden" id="dlte_leave_cat_set_url" value="<?php echo $base_url . 'ajaxcall/delete_leavecat'?>" />
                    <input type="hidden" name="leave_setting_id" id="dlte_lv_sett_id" value=""/>
                    <div class="form-group">
                        <label for="name"><?php echo __("leave name")?></label>
                        <input type="text" id="leave_cat_name" value="" class="form-control"/>
                    </div>
                    <?php echo \Fuel\Core\Form::csrf() ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("no")?></button>
                <button type="button" class="btn btn-danger" id="delete_leave_cat"><i class="fa fa-trash-o"></i> <?php echo __("delete") ?></button>
            </div>
        </div>
    </div>
</div>