<?php  $base_url = \Fuel\Core\Config::get("base_url"); ?>
<section class="container-fluid work-schedule">
    <div class="row">
        <div class="col-md-12">
            <h3><?php echo __("work schedules")?> (<?php echo __("shifts") ?>)</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?php
                $msg = \Fuel\Core\Session::get_flash("a_msg");
                $smsg = \Fuel\Core\Session::get_flash("a_smsg");
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

            <form action="<?php echo $base_url.'administrator/add_shifts' ?>" class="clearfix" method="post">
                <?php echo \Fuel\Core\Form::csrf()?>
                <div class="form-group">
                    <label for="shiftname" class="shift-title">Shift Name</label>
                    <input type="text" name="shift_name" id="shiftname" placeholder="name of the shift" value="" class="form-control"/>
                </div>
                <div class="working-days clearfix" id="working_days">
                    <p class="title">Choose working days</p>
                    <input type="hidden" name="work_days" id="work_days_input"/>
                    <div class="days-tile">
                        <input type="checkbox" id="w_sun" name="w_days[]" value="SUN" data-day="sun"/>
                        <label for="w_sun" class="w_label" data-input="sun">Sun</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="w_mon" name="w_days[]" value="MON" data-day="mon"/>
                        <label for="w_mon" class="w_label" data-input="mon">Mon</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="w_tue" name="w_days[]" value="TUE" data-day="tue"/>
                        <label for="w_tue" class="w_label" data-input="tue">Tue</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="w_wed" name="w_days[]" value="WED" data-day="wed"/>
                        <label for="w_wed" class="w_label" data-input="wed">Wed</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="w_thu" name="w_days[]" value="THU" data-day="thu"/>
                        <label for="w_thu" class="w_label" data-input="thu">Thu</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="w_fri" name="w_days[]" value="FRI" data-day="fri"/>
                        <label for="w_fri" class="w_label" data-input="fri">Fri</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="w_sat" name="w_days[]" value="SAT" data-day="sat"/>
                        <label for="w_sat" class="w_label" data-input="sat">Sat</label>
                    </div>
                </div>
                <div class="dayoff-days clearfix" id="dayoff-days">
                    <p class="title">Choose day-off days</p>
                    <input type="hidden" name="day_off" id="day_off_input" />
                    <div class="days-tile">
                        <input type="checkbox" id="d_sun" name="d_days" value="SUN" data-day="sun"/>
                        <label for="d_sun" class="d_label" data-input="sun">Sun</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="d_mon" name="d_days" value="MON" data-day="mon"/>
                        <label for="d_mon" class="d_label" data-input="mon">Mon</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="d_tue" name="d_days" value="TUE" data-day="tue"/>
                        <label for="d_tue" class="d_label" data-input="tue">Tue</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="d_wed" name="d_days" value="WED" data-day="wed"/>
                        <label for="d_wed" class="d_label" data-input="wed">Wed</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="d_thu" name="d_days" value="THU" data-day="thu"/>
                        <label for="d_thu" class="d_label" data-input="thu">Thu</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="d_fri" name="d_days" value="FRI" data-day="fri"/>
                        <label for="d_fri" class="d_label" data-input="fri">Fri</label>
                    </div>
                    <div class="days-tile">
                        <input type="checkbox" id="d_sat" name="d_days" value="SAT" data-day="sat"/>
                        <label for="d_sat" class="d_label" data-input="sat">Sat</label>
                    </div>
                </div>
                <div class="shifts-schedule clearfix">
                    <p class="title">Start of Shift</p>
                    <div class="form-group">
                        <label for="s_hr">Hour</label>
                        <select name="s_hr" id="s_hr" class="form-control">
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
                    </div>
                    <div class="form-group">
                        <label for="s_min">Minute</label>
                        <select name="s_min" id="s_min" class="form-control">
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
                        <label for="s_sec">Second</label>
                        <select name="s_sec" id="s_sec" class="form-control">
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
                </div>
                <div class="shifts-schedule clearfix">
                    <p class="title">End of Shift</p>
                    <div class="form-group">
                        <label for="e_hr">Hour</label>
                        <select name="e_hr" id="e_hr" class="form-control">
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
                    </div>
                    <div class="form-group">
                        <label for="e_min">Minute</label>
                        <select name="e_min" id="e_min" class="form-control">
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
                        <label for="e_sec">Second</label>
                        <select name="e_sec" id="e_sec" class="form-control">
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
                </div>
                <button class="btn btn-default pull-right"><i class="fa fa-plus-square-o"></i> Add</button>
            </form>
        </div>
        <div class="col-md-8">
            <?php
            $msg = \Fuel\Core\Session::get_flash("l_msg");
            $smsg = \Fuel\Core\Session::get_flash("l_smsg");
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
            <table class="table">
                <caption><h3>Shift List</h3></caption>
                <thead>
                    <tr>
                        <th>Shift Name</th>
                        <th>Work Days</th>
                        <th>Day Offs</th>
                        <th>Start of Shift</th>
                        <th>End of Shift</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($shifts) > 0):?>
                        <?php foreach($shifts as $shift):?>
                            <tr id="<?php echo $shift->shift_id?>">
                                <td class="shift_name"><?php echo $shift->shift_name; ?></td>
                                <td class="work_days"><?php echo $shift->work_days; ?></td>
                                <td class="day_off"><?php echo $shift->day_off; ?></td>
                                <td class="start_shift"><?php echo $shift->start_shift; ?></td>
                                <td class="end_shift"><?php echo $shift->end_shift; ?></td>
                                <td>
                                    <button class="btn btn-default btn-edit-shift"
                                        data-shift-id="<?php echo $shift->shift_id?>"
                                        data-shift-name="<?php echo $shift->shift_name ?>"
                                        data-work-days="<?php echo $shift->work_days ?>"
                                        data-dayoff-days="<?php echo $shift->day_off ?>"
                                        data-start-shift="<?php echo $shift->start_shift?>"
                                        data-end-shift="<?php echo $shift->end_shift?>"
                                    >
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <button class="btn btn-default btn-delete-shift"
                                            data-shift-id="<?php echo $shift->shift_id?>"
                                            data-shift-name="<?php echo $shift->shift_name ?>"
                                    >
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="alert alert-info">
                                    <p class="text-center"><i class="fa fa-warning"></i> No shift record found..!</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Edit Modal -->
<div class="modal fade" id="edit_shift_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> processing</span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info" class="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Shift</h4>
            </div>
            <div class="modal-body">
                <form action="#" id="save-shift-form">
                    <input type="hidden" id="update-shift-url" value="<?php echo $base_url .'ajaxcall/update_shift'?>"/>
                    <input type="hidden" name="shift_id" id="edit_shift_id" value="" />
                    <?php echo \Fuel\Core\Form::csrf(); ?>
                    <div class="form-group">
                        <label for="eshiftname" class="title">Shift Name</label>
                        <input type="text" class="form-control" name="shift_name" id="eshiftname" placeholder="shift name" />
                    </div>
                    <div class="form-group">
                        <label for="workdays" class="title">Work Days</label>
                        <input type="text" class="form-control" name="work_days" id="workdays" placeholder="working days" />
                    </div>
                    <div class="form-group">
                        <label for="dayoff" class="title">Day Off</label>
                        <input type="text" class="form-control" name="day_off" id="dayoff" placeholder="day off" />
                    </div>
                    <div class="shifts-schedule clearfix">
                        <p class="title">Start of Shift</p>
                        <div class="form-group">
                            <label for="hr">Hour</label>
                            <select name="s_hr" id="sedit_hr" class="form-control">
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
                        </div>
                        <div class="form-group">
                            <label for="min">Minute</label>
                            <select name="s_min" id="sedit_min" class="form-control">
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
                            <label for="sec">Second</label>
                            <select name="s_sec" id="sedit_sec" class="form-control">
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
                    </div>
                    <div class="shifts-schedule clearfix">
                        <p class="title">End of Shift</p>
                        <div class="form-group">
                            <label for="hr">Hour</label>
                            <select name="e_hr" id="eedit_hr" class="form-control">
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
                        </div>
                        <div class="form-group">
                            <label for="min">Minute</label>
                            <select name="e_min" id="eedit_min" class="form-control">
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
                            <label for="sec">Second</label>
                            <select name="e_sec" id="eedit_sec" class="form-control">
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
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="btn-save-shift"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete_shift_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="loader hide"><span class="spin-holder"><i class="fa fa-spinner fa-pulse"></i> processing</span></div>
            <div class="notification_msg hide" id="notification_msg"><span id="close_info" class="close_info"><i class="fa fa-close"></i></span><p class="message_title text-center"></p><p class="message_content text-center"></p></div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete Shift</h4>
            </div>
            <div class="modal-body">
                <form action="#" id="delete-shift-form">
                    <input type="hidden" id="delete-shift-url" value="<?php echo $base_url .'ajaxcall/delete_shift'?>"/>
                    <input type="hidden" name="shift_id" id="delete_shift_id" value="" />
                    <?php echo \Fuel\Core\Form::csrf(); ?>
                    <div class="form-group">
                        <label for="dshiftname" class="title">Shift Name</label>
                        <input type="text" class="form-control" name="shift_name" id="dshiftname" placeholder="shift name" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="btn-delete-shift"><i class="fa fa-trash-o"></i> Delete</button>
            </div>
        </div>
    </div>
</div>


