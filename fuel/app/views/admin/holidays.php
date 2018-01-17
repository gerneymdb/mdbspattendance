<?php  $base_url = \Fuel\Core\Config::get("base_url"); ?>
<section class="container-fluid holidays">
    <?php
        $msg = \Fuel\Core\Session::get_flash("msg");
        $smsg = \Fuel\Core\Session::get_flash("smsg");
    ?>

    <?php if(is_array($msg) && count($msg) > 0 ):?>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <?php
                foreach ($msg as $error) {
                    echo "<div class=\"alert alert-danger text-center\"><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$error."</div>";
                }
                ?>
            </div>
        </div>

    <?php endif;?>

    <?php if(is_array($smsg) && count($smsg) > 0 ):?>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <?php
                foreach ($smsg as $error) {
                    echo "<div class=\"alert alert-success text-center\"><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$error."</div>";
                }
                ?>
            </div>
        </div>

    <?php endif;?>

    <!--start first column-->
    <div class="row">
        <div class = "col-md-6 firstcol">
            <h3 class ="texttwo text-center">
                <?php
                    $prev_year = $year - 1;
                    $next_year = $year + 1;
                ?>
                <a href="<?php echo $base_url.'administrator/holidays/'.$prev_year ?>"><span class="fa fa-arrow-circle-o-left left"></span></a><?php echo $year ?> Holidays <a href="<?php echo $base_url.'administrator/holidays/'.$next_year ?>"><span class="fa fa-arrow-circle-o-right right"></span></a>
            </h3>
            <?php if(count($holidays) > 0):?>
                <?php foreach ($holidays as $holiday):?>
                    <div class="outer-div" id="<?php echo $holiday->holiday_id?>">
                        <?php

                            $holiday_type = ($holiday->type == "Special Holiday") ? "special-holiday" : "regular-holiday";

                        ?>
                        <div class="holiday-info">
                            <p class="text-center">
                                <span class="holiday-title <?php echo $holiday_type ?>" data-holiday-name="<?php echo $holiday->holiday_name ?>"><?php echo $holiday->holiday_name ?></span>
                                <span class="<?php echo $holiday_type ?>">
                                    <i class="holiday-start" data-holiday-start="<?php
                                        $s = strtotime($holiday->start_day);
                                        echo strftime("%Y-%m-%d", $s)
                                    ?>">From:
                                        <?php
                                            $stime = strtotime($holiday->start_day);
                                            echo strftime("%B-%d-%Y", $stime);
                                        ?>
                                    </i>
                                    <i class="holiday-end" data-holiday-end="<?php
                                        $e = strtotime($holiday->end_day);
                                        echo strftime("%Y-%m-%d", $e);
                                    ?>">To:
                                        <?php
                                            $etime = strtotime($holiday->end_day);
                                            echo strftime("%B-%d-%Y", $etime);
                                        ?>
                                    </i>
                                </span>
                                <span class="holiday-type <?php echo $holiday_type ?>" data-holiday-type="<?php echo $holiday->type ?>"><?php echo $holiday->type?></span>
                                <span class="holiday-description"><?php echo $holiday->description?></span>
                                <span class="holiday-with-work" data-holiday-with-work="<?php echo $holiday->with_work ?>">
                                    <?php
                                        $with_work = ($holiday->with_work == 1) ? "fa fa-hourglass" : "fa fa-hourglass-o";
                                    ?>
                                    <i class="<?php echo $with_work ?>"></i>
                                    <?php
                                        echo ($holiday->with_work == 1) ? "With Work" : "No Work";
                                    ?>
                                </span>
                            </p>
                        </div>
                        <div class="inner-div">
                            <button class="btn btn-default edit" type="button" data-holiday-id="<?php echo $holiday->holiday_id?>">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </button>

                            <button class="btn btn-default delete" type="button" data-holiday-id="<?php echo $holiday->holiday_id?>">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else:?>
                <div class="alert alert-info">
                    <p class="text-center"><i class='glyphicon glyphicon-exclamation-sign'></i> <strong>No Record Found</strong></p>
                </div>
            <?php endif;?>
        </div>
        <!--end first column-->

        <!--start second column-->
        <div class = "col-md-6 secondcol">
            <h3>Set Holiday</h3>
            <form action="<?php echo $base_url . 'administrator/set_holiday' ?>" id="set_holiday_form" name="set_holiday_form" method="post">

                <?php echo \Fuel\Core\Form::csrf() ?>

                <div class="form-group">
                    <label for="holiday_name">Holiday Name</label>
                    <input type="text" class="form-control" id="holiday_name" placeholder="Holiday Name" name="holiday_name" value="" />
                </div>

                <div class="form-group">
                    <label for="start_day">Start of Holiday</label>
                    <input type="text" class="form-control" id="set_start_day" placeholder="start_day" value="" name="start_day" />
                </div>

                <div class="form-group">
                    <label for="end_day">End of Holiday</label>
                    <input type="text" class="form-control" id="set_end_day" placeholder="end_day" value="" name="end_day" />
                </div>

                <div class="form-group">
                    <label for="types">Type of Holiday</label>
                    <select name="type" id="types" class="form-control">
                        <option value="Regular Holiday">Regular Holiday</option>
                        <option value="Special Holiday">Special Holiday</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Hdescription">Description</label>
                    <textarea name="description" id="Hdescription" rows="5" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label for="with_work">With Work</label>
                    <select name="with_work" id="with_work" class="form-control">
                        <option value="0">No Work</option>
                        <option value="1">With Work</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-default pull-right" type="submit"><i class="fa fa-save"></i> Submit</button>
                </div>
            </form>
        </div>

        <!--end second column-->
</section>


<!-- Edit Modal -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> processing</span></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Holiday</h4>
            </div>
            <div class="modal-body">
                <form action="" id="edit_form">
                    <input type="hidden" id="holiday_update_url" value="<?php echo $base_url.'ajaxcall/update_holiday'?>" />
                    <input type="hidden" value="" name="holiday_id" />

                    <div class="form-group">
                        <label for="holiday_names">Holiday Name</label>
                        <input type="text" class="form-control" id="holiday_names" placeholder="Holiday Name" name="holiday_name" value="" />
                    </div>

                    <div class="form-group">
                        <label for="start_day">Start of Holiday</label>
                        <input type="text" class="form-control" id="start_day" placeholder="start_day" value="" name="start_day" />
                    </div>

                    <div class="form-group">
                        <label for="end_day">End of Holiday</label>
                        <input type="text" class="form-control" id="end_day" placeholder="end_day" value="" name="end_day" />
                    </div>

                    <div class="form-group">
                        <label for="type">Type of Holiday</label>
                        <select name="type" id="type" class="form-control">
                            <option value="Regular Holiday">Regular Holiday</option>
                            <option value="Special Holiday">Special Holiday</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Edescription">Description</label>
                        <textarea name="description" id="Edescription" rows="5" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="with_work">With Work</label>
                        <select name="with_work" id="with_work" class="form-control">
                            <option value="0">No Work</option>
                            <option value="1">With Work</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save_edit">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> processing</span></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete this Holiday</h4>
            </div>
            <input type="hidden" id="holiday_delete_url" value="<?php echo $base_url.'ajaxcall/delete_holiday'?>" />
            <div class="modal-body">
                <form action="#" id="delete_form">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="delete_holiday">Delete</button>
            </div>
        </div>
    </div>
</div>