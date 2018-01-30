<section class="container-fluid attendance-history">
    <div class="row">
        <div class="col-md-12">
            <p class="legend-title"><i class="fa fa-square-o"></i> <?php echo $lang["legend"]?></p>
            <ul class="legend clearfix">
                <li class="present"><i>.</i> <?php echo $lang["present"] ?></li>
                <li class="absent"><i>.</i> <?php echo $lang["absent"] ?></li>
                <li class="regular-holiday"><i>.</i> <?php echo $lang["regular_holiday"]?></li>
                <li class="special-holiday"><i>.</i> <?php echo $lang["special_holiday"]?></li>
                <li class="leave"><i>.</i> <?php echo $lang["leave"] ?></li>
                <li class="dayoff"><i>.</i> <?php echo $lang["day_off"] ?></li>
                <li class="future"><i>.</i> <?php echo $lang["future_date"] ?></li>
            </ul>
        </div>
    </div>
</section>


<section class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2"><p class="text-center"><?php echo $year?> <?php echo $lang["attendance_heading"]?></p></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $int_month = [
                            "January"    => "01",
                            "February"   => "02",
                            "March"      => "03",
                            "April"      => "04",
                            "May"        => "05",
                            "June"       => "06",
                            "July"       => "07",
                            "August"     => "08",
                            "September"  => "09",
                            "October"    => "10",
                            "November"   => "11",
                            "December"   => "12"
                        ];

                        $day_offs = explode(",", $shift_sched->day_off);

                    ?>
                    <?php foreach ($attendance as $month => $days): ?>
                       <tr>
                           <td><?php echo $month?></td>
                           <td>
                               <?php foreach($days as $key => $record): ?>
                                   <?php

                                        $status = "btn-default";

                                        // get this records date in string format
                                        $may_be_future = $year."-".$int_month[$month]."-".$key;
                                        // get today's date in string format
                                        $current_day = strftime("%Y-%m-%d", time());

                                        // convert it timestamp
                                        $future_time = strtotime($may_be_future);
                                        $today_time = strtotime($current_day);
                                        $name_of_day = strtoupper(strftime("%a", $future_time));


                                        // check if employee is onleave
                                        // leave indicator
                                        $is_leave = false;
                                        if(count($leave_record) > 0){
                                            foreach ($leave_record as $leave){
                                                if($may_be_future." 00:00:00" >= $leave->start_leave && $may_be_future." 23:59:59" <= $leave->end_leave){
                                                    $is_leave = true;
                                                    break;
                                                }
                                            }
                                        }

                                        // check if regular holiday
                                        $is_regular_holiday = false;
                                        if(count($regular_holiday) > 0){
                                            foreach ($regular_holiday as $holiday) {
                                                if($may_be_future." 00:00:00" >= $holiday->start_day && $may_be_future." 23:59:59" <= $holiday->end_day){
                                                    $is_regular_holiday = true;
                                                    break;
                                                }
                                            }
                                        }

                                        // check if special holiday
                                        $is_special_holiday = false;
                                        if(count($special_holiday) > 0){
                                            foreach ($special_holiday as $holiday) {
                                                if($may_be_future." 00:00:00" >= $holiday->start_day && $may_be_future." 23:59:59" <= $holiday->end_day){
                                                    $is_special_holiday = true;
                                                    break;
                                                }
                                            }
                                        }

                                        // check if it is future date
                                        if($record == false && ($today_time < $future_time)){
                                            // this date "$may_be_future" is future time
                                            // and no records yet;
                                            if($is_leave){
                                                // currently on leave
                                                $status = "btn-warning";
                                            }else{
                                                $status = "btn-default";
                                            }

                                        }else {


                                            // if not future time
                                            if(in_array($name_of_day, $day_offs)) {
                                                // its dayoff
                                                $status = "btn-off";

                                            }elseif($is_regular_holiday) {
                                                // its a regular holiday
                                                $status = "btn-rh";
                                            }elseif($is_special_holiday){
                                                // its special holiday
                                                $status = "btn-sh";

                                            }elseif($is_leave){
                                                // employee's leave
                                                $status = "btn-l";

                                            }elseif($record == false){

                                                $status = "btn-danger";

                                            }else {
                                                $status = "btn-success";
                                            }
                                        }

                                   ?>
                                   <button class="btn btn-days <?php echo $status; ?> ">
                                       <span class="in_no"><?php echo $key?></span>
                                       <span class="in_name"><?php echo $name_of_day; ?></span>
                                   </button>
                               <?php endforeach; ?>
                           </td>
                       </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</section>