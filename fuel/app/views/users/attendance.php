<?php

    use \Fuel\Core\Form as Form;

    $base_url = \Fuel\Core\Config::get("base_url");
?>

<?php
$msg = \Fuel\Core\Session::get_flash("msg");
$smsg = \Fuel\Core\Session::get_flash("smsg");
?>

<?php if(is_array($msg) && count($msg) > 0 ):?>
<section class="container-fluid">
    <div class="row">
        <div class="col-md-12">
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
<section class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php
                foreach ($smsg as $error) {
                    echo "<div class=\"alert alert-success text-center\"><i class='glyphicon glyphicon-exclamation-sign'></i>  ".$error."</div>";
                }
            ?>
        </div>
    </div>
</section>
<?php endif;?>

<?php if($rh != false):?>
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                    $sph = $rh;
                    $sph = $rh;
                    $holiday = array_shift($sph);
                ?>
                <div class="alert alert-danger">
                    <p><?php echo $lang["regular_holiday"]?></p>
                    <p><?php echo $holiday->holiday_name?></p>
                    <p><?php echo $holiday->description?></p>
                </div>
            </div>
        </div>
    </section>
<?php endif;?>
<?php if($sh != false):?>
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                    $rhd = $sh;
                    $holiday = array_shift($rhd);
                ?>
                <div class="alert alert-danger">
                    <p class="text-center"><?php echo $lang["special_holiday"]?> <strong><?php echo $holiday->holiday_name?></strong>. <?php echo $holiday->description?></p>
                </div>
            </div>
        </div>
    </section>
<?php endif;?>

<section class="container-fluid" id="attendance">
    <div class="row">
        <div class="col-md-6">
            <div class="started clearfix">
                <?php
                    $date     = strftime("%Y-%b-%d-%A", time());
                    $date     = explode("-", $date);
                    $year     = $date[0];
                    $month    = $date[1];
                    $day      = $date[2];
                    $day_name = $date[3];

                    $holiday = "not-holiday";
                    if($rh != false){
                        $holiday = "holiday";
                    }
                    if($sh != false){
                        $holiday = "holiday";
                    }
                    if($day_name == "Sunday"){
                        $holiday = "holiday";
                    }
                ?>
                <div class="employee">
                    <?php
                    $fullname = $info->fname . " " . $info->mname . " " . $info->lname;
                    ?>
                    <p class="name"><i class="fa fa-user-circle-o"></i> <?php echo ucwords($fullname)?></p>
                </div>
                <div class="date <?php echo $holiday; ?>">
                    <p class="month"><?php echo strtoupper($month) ?></p>
                    <p class="day"><?php echo $day ?></p>
                    <p class="day_name"><?php echo $day_name ?></p>
                    <p class="year"><?php echo $year ?></p>
                </div>
                <div class="time-attendance">
                    <div class="timein">
                        <p><?php echo $lang["timein"]?>:
                        <?php
                           if($timein == ""){
                               echo $timein;
                           }else {
                               if($timein <= $shift_sched->start_shift ){
                                   echo "<span class='early'>". $timein ."</span>";
                               }else{
                                   echo "<span class='late'>". $timein ."</span>";
                               }
                           }
                        ?>
                        </p>
                    </div>
                    <div class="timeout">
                        <p><?php echo $lang["timeout"] ?>:
                        <?php
                            if($timeout == ""){
                                echo $timeout;
                            }else {
                                if($timeout < $shift_sched->end_shift ){
                                    echo "<span class='late'>". $timeout ."</span>";
                                }else{
                                    echo "<span class='early'>". $timeout ."</span>";
                                }
                            }
                        ?>
                        </p>
                    </div>
                </div>
                <div class="digital-clock clearfix">
                    <p><span id="hr"></span>:<span id="min"></span>:<span id="sec"></span></p>
                </div>
            </div>
            <div class="clock">

                <?php if($done_today == false):?>

                    <?php if($rh == false && $sh == false && $l == false && $do == false): ?>
                        <!-- today you are on duty -->
                        <?php if($timed_in == false):?>

                            <!--user will time in-->
                            <?php echo Form::open(array('action'=>'user/timein', 'class'=>'time-form', 'method'=>'post', 'id'=>'timein_form'))?>
                                <?php echo Form::csrf(); ?>
                                <button type="submit" class="btn btn-default btn-lg time-btn" name="timein"><i class="fa fa-clock-o"></i> <?php echo $lang["timein"] ?></button>
                            <?php echo Form::close()?>

                        <?php else:?>

                            <!--user will time out-->
                            <?php echo Form::open(array('action'=>'user/timeout', 'class'=>'time-form', 'method'=>'post', 'id'=>'timeout_form'))?>
                            <?php echo Form::csrf(); ?>
                                <button type="submit" class="btn btn-default btn-lg time-btn"><i class="fa fa-clock-o"></i> <?php echo $lang["timeout"] ?></button>
                            <?php echo Form::close()?>

                        <?php endif;?>

                    <?php else: ?>
                        <!-- its holiday or you are on leave or its your day off -->
                        <?php
                            $rh = ($rh != false) ? array_shift($rh): false;
                            $sh = ($sh != false) ? array_shift($sh): false;
                            $l  = ($l  != false)  ? array_shift($l) : false;
                            $do = ($do != false) ? array_shift($do): false;
                        ?>
                        <div class="alert alert-info">
                            <!-- leave -->
                            <?php if($l != false):?>
                                <p class="text-info text-center"><?php echo $lang["on_leave"]?> <strong><?php echo $l->type?></strong></p>
                            <?php endif; ?>

                            <!-- day off -->
                            <?php if($do != false):?>
                                <p class="text-info text-center"><?php echo $lang["your_day_off"]?> <strong><?php echo $lang["day_off"]?></strong></p>
                            <?php endif; ?>

                            <!-- special holiday -->
                            <?php if($sh != false):?>
                                <p class="text-info text-center"><?php echo $lang["special_holiday"]?> <strong><?php echo $sh->holiday_name?></strong></p>
                            <?php endif; ?>

                            <!-- regular holiday -->
                            <?php if($rh != false):?>
                                <p class="text-info text-center"><?php echo $lang["regular_holiday"]?> <strong><?php echo $rh->holiday_name; ?></strong></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>

                    <div class="alert">
                        <h3><?php echo $lang["work_done"] ?></h3>
                    </div>

                <?php endif;?>

            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-calendar"></i> <?php echo $lang["schedule_heading"]?></h3>
                </div>
                <div class="panel-body">
                </div>
                <table class="table table-responsive attendance-table">
                    <tbody>
                    <tr>
                        <th><?php echo $lang["work_days"]?></th><td><?php echo $shift_sched->work_days ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang["day_off"]?></th><td><?php echo $shift_sched->day_off ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang["starts_at"] ?></th><td><?php echo $shift_sched->start_shift ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang["ends_at"] ?></th><td><?php echo $shift_sched->end_shift ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-tasks"></i> <?php echo $lang["activity_heading"]?></h3>
                    <input type="hidden" value="<?php echo $base_url . 'ajaxcall/getTotalTime'?>" id="total_time_url">
                </div>
                <table class="table table-responsive attendance-table">
                    <tbody>
                        <tr>
                            <th><?php echo $lang["total_work"]?></th><td id="total_time"><?php echo $total_time; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
