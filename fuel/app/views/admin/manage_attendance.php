<?php  $base_url = \Fuel\Core\Config::get("base_url"); ?>
<section class="container-fluid manage-attendance" id="manage-attendance">
    <?php
    $msg = \Fuel\Core\Session::get_flash("msg");
    $smsg = \Fuel\Core\Session::get_flash("smsg");
    ?>

    <?php if(is_array($msg) && count($msg) > 0 ):?>

            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <?php
                    foreach ($msg as $error) {
                        echo "<div class=\"alert alert-danger text-center\"><i class='fa fa-warning'></i>  ".$error."</div>";
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
                        echo "<div class=\"alert alert-success text-center\"><i class='fa fa-warning'></i>  ".$error."</div>";
                    }
                    ?>
                </div>
            </div>

    <?php endif;?>

    <?php

    $days = "";
    $month_selected = "";
    $year_selected = "";

    // array of months
    $months = array(
        __("january"),
        __("february"),
        __("march"),
        __("april"),
        __("may"),
        __("june"),
        __("july"),
        __("august"),
        __("september"),
        __("october"),
        __("november"),
        __("december")
    );

    $no_days = 0;

    if(is_array($attendance)){

        if(array_key_exists("month", $attendance) && array_key_exists("year", $attendance)){
            $month_selected = $month = $attendance["month"];
            $year_selected = $year  = $attendance["year"];

            // get no of days in a month in a given year
            $no_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $days .= "<tr><th></th><th colspan={$no_days}>{$months[ltrim($month, '0') - 1]} {$year} ".__("attendance")."</th></tr>";

            $days .= "<tr><th>".__("name")."</th>";
            for ($x = 1; $x <= $no_days; $x++) {
                $day = ($x < 10) ? "0".$x : $x;
                $days .= "<th>$day</th>";
            }
            $days .= "</tr>";

            // remove month
            unset($attendance["month"]);
            unset($attendance["year"]);
        }

    }

    ?>

    <div class="row form-row">
        <div class="col-md-12">
            <h4 class="text-center"><i class="fa fa-calendar-check-o"></i> <?php echo __("view attendance") ?></h4>
            <form action="<?php echo $base_url . 'administrator/fetch_attendance'?>" class="form-inline" method="post" id="search_attendance">
                <?php echo \Fuel\Core\Form::csrf(); ?>
                <div class="form-group">
                    <label for="month">Month</label>
                    <select name="month" id="month" class="form-control">
                        <?php
                           if($month_selected != "" & $year_selected != ""){
                               //remove leading zeros first
//                               $month_selected = ltrim($month_selected, "0");
                               // format string
                               $format = '%d'.$month_selected;
                               $month_num = ($month_selected < 10)? sprintf($format,"0") : $month_selected;

                               echo "<option value='".$month_num."' selected>{$months[ltrim($month_selected, '0') - 1]}</option>";
                           }
                        ?>
                        <option value="01"><?php echo __("january") ?></option>
                        <option value="02"><?php echo __("february") ?></option>
                        <option value="03"><?php echo __("march") ?></option>
                        <option value="04"><?php echo __("april") ?></option>
                        <option value="05"><?php echo __("may") ?></option>
                        <option value="06"><?php echo __("june") ?></option>
                        <option value="07"><?php echo __("july") ?></option>
                        <option value="08"><?php echo __("august") ?></option>
                        <option value="09"><?php echo __("september") ?></option>
                        <option value="10"><?php echo __("october") ?></option>
                        <option value="11"><?php echo __("november") ?></option>
                        <option value="12"><?php echo __("december") ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year"><?php echo __("year") ?></label>
                    <input type="number" class="form-control" name="year" id="year" value="<?php echo (isset($year_selected) && $year_selected != "")? $year_selected : strftime('%Y', time())?>" />
                </div>
                <button type="submit" class="btn btn-default"><?php echo __("submit") ?></button>
                <button type="button" class="btn btn-default pull-right" id="attendance_count"><i class="fa fa-calendar"></i> <?php echo __("attendance report") ?></button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <?php if(is_array($attendance) && count($attendance) > 0):?>
                <!--with records-->
                <table class="table table-bordered attendance-table" id="attendance-tables">
                    <caption class="text-center"><strong><?php echo $months[ltrim($month_selected, '0') - 1] . " " . $year_selected?></strong></caption>
                    <tbody class="attendance-body" id="attendance-body">

                    <?php if(is_array($attendance) && count($attendance) > 0): ?>
                        <!---->

                        <?php foreach ($attendance as $employee => $record):?>
                            <tr>

                                <td><?php echo $employee_names[$employee]?></td>

                                <td>
                                    <?php for ($x = 1; $x <= $no_days; $x++):?>

                                        <?php
                                            $day = ($x < 10)? "0".$x : $x;
                                            $attendance_date = $year_selected."-".$month_selected."-".$day;

                                            // convert to timestamp
                                            $time = strtotime($attendance_date);
                                            // convert to readable format
                                            $date = "<span class='a_date'>".strftime("%a", $time)."</span>";
                                            $date .= "<span class='a_date'>".strftime("%d", $time)."</span>";

                                            $now = time();
                                        ?>

                                        <?php if(array_key_exists($employee, $attendance) && array_key_exists($attendance_date, $record)):?>
                                            <!-- employee userid exist in attendance record --><!-- this attendance date, the employee has a record on the database -->
                                            <!-- means this employee is present -->
                                            <a href="#" class="btn btn-success btn-attdnc-present" id="<?php echo $employee."_".$attendance_date."pr"?>"
                                               data-name="<?php echo $employee_names[$employee] ?>"
                                               data-att-id="<?php echo $record[$attendance_date]['attendance_id']?>"
                                               data-timein="<?php echo (empty($record[$attendance_date]['timein']))? $attendance_date : $record[$attendance_date]['timein'] ?>"
                                               data-timeout="<?php echo (empty($record[$attendance_date]['timeout']))? $attendance_date : $record[$attendance_date]['timeout']?>"
                                               data-status="<?php echo $record[$attendance_date]['status']?>">
                                                <?php echo $date; ?>
                                            </a>

                                        <?php else:?>

                                            <!-- there is no record on the database; -->
                                            <?php
                                                // employees shift id
                                                $shift_id = $employees[$employee]->shift_id;

                                                // employees dayoff schedule
                                                $shift = $shifts[$shift_id];

                                                // the day offs
                                                $day_offs = explode(",", $shift->day_off);

                                                $today = strftime("%a", $time);
                                                $today = strtoupper($today);


                                                $is_regular_holiday = false;
                                                // check if regular holiday
                                                if(count($regular_holiday) > 0){
                                                    foreach ($regular_holiday as $holiday) {
                                                        if($attendance_date." 00:00:00" >= $holiday->start_day && $attendance_date." 23:59:59" <= $holiday->end_day){
                                                            $is_regular_holiday = true;
                                                            break;
                                                        }
                                                    }
                                                }

                                                $is_special_holiday = false;
                                                if(count($special_holiday) > 0){
                                                    foreach ($special_holiday as $holiday) {
                                                        if($attendance_date." 00:00:00" >= $holiday->start_day && $attendance_date." 23:59:59" <= $holiday->end_day){
                                                            $is_special_holiday = true;
                                                            break;
                                                        }
                                                    }
                                                }

                                            ?>

                                            <!-- maybe it the employees dayoff -->
                                            <?php if(in_array($today, $day_offs)):?>

                                                <a href="#" class="btn btn-off" id="<?php echo $employee."_".$attendance_date."do"?>">
                                                    <?php
                                                        echo "<span class='a_date'>".strftime("%a", $time)." ".strftime("%d", $time)."</span><span class='a_date'><small>".__("day off")."</small></span>";
                                                    ?>
                                                </a>

                                            <?php elseif($is_regular_holiday):?>

                                                <a href="#" class="btn btn-rh" id="<?php echo $employee."_".$attendance_date."rh"?>">
                                                    <?php
                                                        echo "<span class='a_date'>".strftime("%a", $time)." ".strftime("%d", $time)."</span><span class='a_date'><small>".__("regular holiday")."</small></span>";
                                                    ?>
                                                </a>

                                            <?php elseif($is_special_holiday):?>

                                                <a href="#" class="btn btn-sh" id="<?php echo $employee."_".$attendance_date."sh" ?>">
                                                    <?php
                                                    echo "<span class='a_date'>".strftime("%a", $time)." ".strftime("%d", $time)."</span><span class='a_date'><small>".__("special holiday")."</small></span>";
                                                    ?>
                                                </a>

                                            <?php elseif($now < $time): ?>

                                                <a href="#" class="btn btn-default" id="<?php echo $employee."_".$attendance_date."df"?>">
                                                    <?php
                                                    echo $date;
                                                    ?>
                                                </a>

                                            <?php else:?>

                                                <!-- else employee is absent -->
                                                <a href="#" class="btn btn-danger btn-attendance-absent" id="<?php echo $employee."_".$attendance_date."ab"?>"
                                                   data-name="<?php echo $employee_names[$employee] ?>"
                                                   data-userid="<?php echo $employee?>"
                                                   data-date="<?php echo $attendance_date?>">
                                                    <?php echo $date; ?>
                                                </a>

                                            <?php endif;?>


                                        <?php endif;?>

                                    <?php endfor;?>
                                </td>

                            </tr>
                        <?php endforeach;?>

                    <?php else:?>

                        <tr>
                            <td>
                                <div class="alert alert-danger">
                                    <p class="text-center"><i class="fa fa-warning"></i> <?php echo __("no records for this date") ?></p>
                                </div>
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>
                </table>

            <?php else:?>
                <!-- without records -->
                <div class="alert alert-info">
                    <p class="text-center"><i class="fa fa-warning"></i> <strong> <?php echo __("no records for this date") ?></strong></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Present Edit Modal -->
<div class="modal fade" id="edt_present" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> <?php echo __("processing") ?></span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info" class="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __("present edit") ?></h4>
            </div>
            <div class="modal-body">
                <form action="" class="present_edit" id="present_edit">
                    <input type="hidden" id="attendance_id" name="attendance_id" value="" />
                    <input type="hidden" id="present_url" value="<?php echo $base_url . 'ajaxcall/update_present'?>" />
                    <input type="hidden" id="date_of_attendance" name="date_of_attendance" value="" />
                    <div id="atteditcsrftoken">
                        <?php echo \Fuel\Core\Form::csrf() ?>
                    </div>
                    <div class="form-group">
                        <label for="fullname"><?php echo __("full name") ?></label>
                        <input type="text" id="fullname_timein" value="" class="form-inline"/>
                    </div>
                    <div class="form-group">
                        <label for="datetimein"><?php echo __("time in") ?></label>
                        <input type="text" id="datetimein" value="" name="<?php echo __("date time in") ?>" class="form-inline"/>
                        <select name="hrtimein" id="hrtimein" class="form-inline">
                            <option value="00">00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                        </select>
                        <select name="mintimein" id="mintimein" class="form-inline">
                            <option value="00">00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                        </select>
                        <select name="sectimein" id="sectimein" class="form-inline">
                            <option value="00">00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="datetimeout"><?php echo __("time out")?></label>
                        <input type="text" id="datetimeout" value="" name="datetimeout" class="form-inline"/>
                        <select name="hrtimeout" id="hrtimeout" class="form-inline">
                            <option value="00">00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                        </select>
                        <select name="mintimeout" id="mintimeout" class="form-inline">
                            <option value="00">00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                        </select>
                        <select name="sectimeout" id="sectimeout" class="form-inline">
                            <option value="00">00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="timeinstatus"><?php echo __("status") ?></label>
                        <select name="status" id="timeinstatus" class="form-inline">
                            <option value="Present"><?php echo __("present") ?></option>
                            <option value="Absent"><?php echo __("absent") ?></option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("close") ?></button>
                <button type="button" class="btn btn-primary" id="edit_present_btn"><i class="fa fa-save"></i> <?php echo __("save") ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Absent Edit Modal -->
<div class="modal fade" id="edt_absent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> <?php echo __("processing") ?></span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info" class="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __("absent edit") ?></h4>
            </div>
            <div class="modal-body">
                <form action="" class="absent_edit" id="absent_edit">
                    <input type="hidden" id="userid" name="userid" value="" />
                    <input type="hidden" id="absent_url" value="<?php echo $base_url . 'ajaxcall/update_absent'?>" />
                    <div class="form-group">
                        <label for="fullname"><?php echo __("full name") ?></label>
                        <input type="text" id="absent_fullname" value="" class="form-inline"/>
                    </div>
                    <div class="form-group">
                        <label for="datetimein"><?php echo __("time in") ?></label>
                        <input type="text" id="a_datetimein" value="" name="a_datetimein" class="form-inline"/>
                        <select name="a_hrtimein" id="a_hrtimein" class="form-inline">
                            <option value="00">00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09" selected>09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                        </select>
                        <select name="a_mintimein" id="a_mintimein" class="form-inline">
                            <option value="00" selected>00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                        </select>
                        <select name="a_sectimein" id="a_sectimein" class="form-inline">
                            <option value="00" selected>00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a_datetimeout"><?php echo __("time out") ?></label>
                        <input type="text" id="a_datetimeout" value="" name="a_datetimeout" class="form-inline"/>
                        <select name="a_hrtimeout" id="a_hrtimeout" class="form-inline">
                            <option value="00">00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18" selected>18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                        </select>
                        <select name="a_mintimeout" id="a_mintimeout" class="form-inline">
                            <option value="00" selected>00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                        </select>
                        <select name="a_sectimeout" id="a_sectimeout" class="form-inline">
                            <option value="00" selected>00</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a_timeinstatus"><?php echo __("status") ?></label>
                        <select name="status" id="a_timeinstatus" class="form-inline">
                            <option value=""></option>
                            <option value="Absent"><?php echo __("absent") ?></option>
                            <option value="Present"><?php echo __("present") ?></option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("close") ?></button>
                <button type="button" class="btn btn-primary" id="edt_btn_absent"><i class="fa fa-save"></i> <?php echo __("save") ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="attendance_count_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __("this month's attendance") ?></h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <caption><h4><?php echo __("daily attendance count") ?></h4></caption>
                    <tbody>
                        <?php  $total_monthly_attendance = 0; ?>
                        <?php if(is_array($attendance) && count($attendance) > 0): ?>
                            <?php
                                $day_count = 1;
                                $record_table = "<tr>";
                            ?>
                            <!---->
                            <?php for ($x = 1; $x <= $no_days; $x++):?>


                                    <?php
                                    $day = ($x < 10)? "0".$x : $x;
                                    $attendance_date = $year_selected."-".$month_selected."-".$day;

                                    // convert to timestamp
                                    $time = strtotime($attendance_date);
                                    // convert to readable format
                                    $date = "<span class='a_date text-center'>".strftime("%a", $time)." ".strftime("%d", $time)."</span>";

                                    $now = time();
                                    $attendance_count = 0;
                                    ?>

                                    <?php foreach ($attendance as $employee => $record):?>

                                        <?php if(array_key_exists($employee, $attendance) && array_key_exists($attendance_date, $record)):?>
                                            <!-- employee userid exist in attendance record --><!-- this attendance date, the employee has a record on the database -->
                                            <!-- means this employee is present -->
                                            <?php $attendance_count++; ?>
                                            <?php $total_monthly_attendance++; ?>
                                        <?php endif;?>

                                    <?php endforeach;?>

                                <?php if($day_count <= 7):?>

                                    <?php
                                        $record_table .= "<td>{$date}<span class='a_date text-center'>".$attendance_count."</span></td>";
                                    ?>
                                    <?php $day_count++ ?>

                                <?php else:?>

                                    <?php
                                        $record_table .= "<td>{$date}<span class='a_date text-center'>".$attendance_count."</td></tr></tr>";
                                        $day_count = 0;
                                    ?>

                                <?php endif;?>

                            <?php endfor;?>

                            <?php echo $record_table; ?>

                        <?php else:?>

                            <tr>
                                <td>
                                    <div class="alert alert-danger">
                                        <p class="text-center"><i class="fa fa-warning"></i> <?php echo __("no records for this date") ?></p>
                                    </div>
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="alert alert-info">
                    <p><strong><?php echo __("this month's attendance") ?>:</strong> <?php echo $total_monthly_attendance; ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("close") ?></button>
            </div>
        </div>
    </div>
</div>