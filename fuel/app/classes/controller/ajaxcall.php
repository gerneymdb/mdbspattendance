<?php

class Controller_Ajaxcall extends \Fuel\Core\Controller {

    /**
     * Calculate and returns the number of hours you
     * spent
     */
    public function get_getTotalTime(){
        // get username(userid) of currently logged in user
        $userid = Session::get("username");

        // get date today
        $today = strftime("%Y-%m-%d", time());
        // get last number of the string
        $str = explode("-", $today);

        $last_no = $str[count($str) - 1];
        $last_no++;


        $end_of_the_day = $str[0] . "-" . $str[1] . "-" . $last_no;

        $result = Model_Attendance::find("all", array(
            "where" => array(
                array("userid", "=", $userid),
                array("timein", ">=", $today. " 00:00:00"),
                array("timein", "<", $end_of_the_day)
            )
        ));

        if(count($result) < 1){
            echo "00 hrs 00 min 00 sec";
        }else {

            // timein time
            $attendance = array_shift($result);

            // todays time
            $todaystime = strftime("%Y-%m-%d %H:%M:%S", time());

            $datetime1 = new DateTime($attendance->timein);
            $datetime2 = new DateTime($todaystime);

            $time_diff = $datetime1->diff($datetime2);

            echo $time_diff->format("%H hrs %I min %S sec");
        }
    }

    /**
     * Updates the leave application status to rejected
     */
    public function post_reject(){
        try{

            $leave_id = \Fuel\Core\Input::post("leave_id");
            $comment  = \Fuel\Core\Input::post("comment");

            $position = \Auth\Auth::get_profile_fields("position");
            $fname    = \Auth\Auth::get_profile_fields("fname");
            $lname    = \Auth\Auth::get_profile_fields("lname");


            $record = Model_Leave::find($leave_id);
            $record->comments = $comment;
            $record->status = "rejected";

            $record->approved_by = $position.": ".$fname." ".$lname;

            $result = $record->save();

            if($result){
                echo $leave_id;
            }else {
                echo 0;
            }

        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Updates the leave application status to approved
     */
    public function post_approve(){
        try{

            $leave_id = \Fuel\Core\Input::post("leave_id");
            $comment  = \Fuel\Core\Input::post("comment");

            $position = \Auth\Auth::get_profile_fields("position");
            $fname    = \Auth\Auth::get_profile_fields("fname");
            $lname    = \Auth\Auth::get_profile_fields("lname");

            $record = Model_Leave::find($leave_id);
            $record->comments = $comment;
            $record->status = "approved";
            $record->approved_by = $position.": ".$fname." ".$lname;

            $result = $record->save();

            if($result){
                echo $leave_id;
            }else {
                echo 0;
            }

        }catch (Exception $e) {

            die($e->getMessage());

        }
    }

    /**
     * Update holidays information
     */
    public function post_update_holiday(){
        try{

            $holiday_id = \Fuel\Core\Input::post("holiday_id");
            $holiday_name = \Fuel\Core\Input::post("holiday_name");
            $start_day = \Fuel\Core\Input::post("start_day");
            $end_day = \Fuel\Core\Input::post("end_day");
            $type = \Fuel\Core\Input::post("type");
            $description = \Fuel\Core\Input::post('description');
            $with_work = \Fuel\Core\Input::post("with_work");

            $record = Model_Holidays::find($holiday_id);
            $record->holiday_name = $holiday_name;
            $record->start_day = $start_day . " 00:00:00";
            $record->end_day = $end_day . " 23:59:59";
            $record->type = $type;
            $record->description = $description;
            $record->with_work = $with_work;

            $result = $record->save();
            if($result){
                echo 1;
            }else {
                echo 0;
            }

        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Delete Holiday
     */
    public function post_delete_holiday(){

        try {

            $holiday_id = \Fuel\Core\Input::post("holiday_id");

            $record = Model_Holidays::find($holiday_id);
            $result = $record->delete();

            if($result){
                echo 1;
            }else {
                echo 0;
            }

        }catch (Exception $e){
            die($e->getMessage());
        }

    }

    /**
     * On Manage Employees Page, Reset Employees Password
     */
    public function  post_reset_password(){

        $username = \Fuel\Core\Input::post("username");

        $new_pwd = \Auth\Auth::reset_password($username);

        echo $new_pwd;
    }

    /**
     *  save employee info
     */
    public function post_save_employee_info(){
        try{

            // validate form input
            $val = \Fuel\Core\Validation::forge("add_employee");

            // validation rules
            $val->add_field("fname", "First Name", "required");
            $val->add_field("lname", "Last Name", "required");
            $val->add_field("shift_id", "Shift", "required");
            $val->add_field("userid", "User ID", "required");
            $val->add_field("email", "Email Address", "required|valid_email");

            if(!$val->run()){

                echo "Please review edit information form";

            }else {


                $userid      = \Fuel\Core\Input::post("userid");
                $employee_id = \Fuel\Core\Input::post("employee_id");
                $fname       = \Fuel\Core\Input::post("fname");
                $mname       = \Fuel\Core\Input::post("mname");
                $lname       = \Fuel\Core\Input::post("lname");
                $shift_id    = \Fuel\Core\Input::post("shift_id");
                $email       = \Fuel\Core\Input::post("email");

                $emp_record = Model_Employee::find($employee_id);

                $emp_record->fname    = $fname;
                $emp_record->mname    = $mname;
                $emp_record->lname    = $lname;
                $emp_record->shift_id = $shift_id;
                $result = $emp_record->save();

                $result2 = Auth\Auth::update_user(array(
                    "email" => $email
                ), $userid);

                echo ($result && $result2) ? true : false;

            }


        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    /**
     *  get employee record
     */
    public function post_delete_employee_record(){
        try{

            $userid = \Fuel\Core\Input::post("userid");

            $emp_record = Model_Employee::find("all", array(
                "where" => array(
                    array("userid", "=", $userid)
                )
            ));

            $employee_table =  false;

            if(count($emp_record) > 0){
                $record = array_shift($emp_record);

                // delete
                $employee_table = $record->delete();
            }

            $user_rec = Model_Login::find("all", array(
                "where" => array(
                    array("username", "=", $userid)
                )
            ));

            $user_table = false;
            if(count($user_rec) > 0){
                $record = array_shift($user_rec);
                // delete
                $user_table = $record->delete();
            }

            // both
            if($employee_table && $user_table){
                echo true;
            }else {
                echo false;
            }

        }catch(Exception $e){
            die($e->getMessage());
        }
    }


    /**
     *  update the clockin or clockout
     *  of the user if the present button is
     *  click
     *
     */
    public function post_update_present(){
        try{

            // get all post variables

            $attendance_id = \Fuel\Core\Input::post("attendance_id");

            // time in && time out values
            $datetimein    = \Fuel\Core\Input::post("datetimein");
            $hrtimein      = \Fuel\Core\Input::post("hrtimein");
            $mintimein     = \Fuel\Core\Input::post("mintimein");
            $sectimein     = \Fuel\Core\Input::post("sectimein");
            $datetimeout   = \Fuel\Core\Input::post("datetimeout");
            $hrtimeout     = \Fuel\Core\Input::post("hrtimeout");
            $mintimeout    = \Fuel\Core\Input::post("mintimeout");
            $sectimeout    = \Fuel\Core\Input::post("sectimeout");

            $status        = \Fuel\Core\Input::post("status");

            // timein value
            $timein = $datetimein." ".$hrtimein.":".$mintimein.":".$sectimein;
            // timeout value
            $timeout = $datetimeout." ".$hrtimeout.":".$mintimeout.":".$sectimeout;

            // update record
            if($status == "Present"){
                // means admin did change the present to absent
                $record = Model_Attendance::find($attendance_id);

                $record->timein  = $timein;
                $record->timeout = $timeout;
                $record->status  = $status;

                $result = $record->save();

                if($result){
                    echo true;
                }else {
                    echo false;
                }

            }elseif($status == "Absent"){
                // means admin decide to make this employee absent this day
                $record = Model_Attendance::find($attendance_id);
                $result = $record->delete();

                if($result){
                    echo true;
                }else{
                    echo false;
                }

            }else{

                die("Status is not Present nor Absent");
            }

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    /**
     *  Update Absent Date of Employee
     */
    public function post_update_absent(){

        try{

            // get all post variables
            $userid = \Fuel\Core\Input::post("userid");

            // time in && time out values
            $datetimein    = \Fuel\Core\Input::post("a_datetimein");
            $hrtimein      = \Fuel\Core\Input::post("a_hrtimein");
            $mintimein     = \Fuel\Core\Input::post("a_mintimein");
            $sectimein     = \Fuel\Core\Input::post("a_sectimein");
            $datetimeout   = \Fuel\Core\Input::post("a_datetimeout");
            $hrtimeout     = \Fuel\Core\Input::post("a_hrtimeout");
            $mintimeout    = \Fuel\Core\Input::post("a_mintimeout");
            $sectimeout    = \Fuel\Core\Input::post("a_sectimeout");

            $status        = \Fuel\Core\Input::post("status");

            // timein value
            $timein = $datetimein." ".$hrtimein.":".$mintimein.":".$sectimein;
            // timeout value
            $timeout = $datetimeout." ".$hrtimeout.":".$mintimeout.":".$sectimeout;

            if($status == "Present"){
                // means admin change the status of this employee on this date
                // admin made employee present
                $record = Model_Attendance::forge();
                $record->userid = $userid;
                $record->timein  = $timein;
                $record->timeout = $timeout;
                $record->status  = $status;

                $result = $record->save();

                if($result){

                    echo true;

                }else {
                    echo false;
                }

            }elseif($status == "Absent"){

                echo "No changes";

            }else{

                die("Status is not Present nor Absent");
            }

        }catch(Exception $e){

            die($e->getMessage());

        }

    }

    public function post_update_leavecat(){

        try{

            if(!\Fuel\Core\Security::check_token()){

                echo "Illegal Operation. Missing token. Hit refresh";

            }else {

                // validate form input
                $val = \Fuel\Core\Validation::forge("edit_leave_cateogry");

                // validation rules
                $val->add_field("leave_name", "Leave Name", "required");
                $val->add_field("days_alloted", "Days alloted", "required|valid_string[numeric]");

                if(!$val->run()){

                    $errors = $val->error_message();

                    $msg = "";
                    foreach ($errors as $key => $error){
                        $msg .= "{$error}. ";
                    }

                    echo $msg;

                }else {

                    $leave_setting_id = \Fuel\Core\Input::post("leave_setting_id");
                    $leave_name       = \Fuel\Core\Input::post("leave_name");
                    $days_alloted     = \Fuel\Core\Input::post("days_alloted");

                    $record = Model_Leavesettings::find($leave_setting_id);
                    $record->leave_name   = $leave_name;
                    $record->days_alloted = $days_alloted;
                    $result = $record->save();

                    if($result){

                        $sve_record = ["leave_settings_id" => $leave_setting_id, "leave_name" => $leave_name, "days_alloted" => $days_alloted];
                        $sve_record = json_encode($sve_record);
                        echo $sve_record;

                    }else{
                        echo "Unable to save changes.";
                    }

                }// form validations

            }// token check


        }catch (Exception $e) {
            die($e->getMessage());
        }

    }

    public function post_delete_leavecat(){
        try{

            if(!\Fuel\Core\Security::check_token()){

                echo "Illegal Operation. Missing token. Hit refresh";

            }else {

                // validate form input
                $val = \Fuel\Core\Validation::forge("delete_leave_category");

                // validation rules
                $val->add_field("leave_setting_id", "leave setting id", "required|valid_string[numeric]");

                if(!$val->run()){

                    $errors = $val->error_message();

                    $msg = "";
                    foreach ($errors as $key => $error){
                        $msg .= "{$error}. ";
                    }

                    echo $msg;

                }else {

                    $leave_setting_id = \Fuel\Core\Input::post("leave_setting_id");

                    $record = Model_Leavesettings::find($leave_setting_id);
                    $result = $record->delete();

                    if($result){

                        $sve_record = ["leave_settings_id" => $leave_setting_id];
                        $sve_record = json_encode($sve_record);
                        echo $sve_record;

                    }else{
                        echo "Unable to delete.";
                    }

                }// form validations

            }// token check


        }catch (Exception $e) {
            die($e->getMessage());
        }

    }


    public function post_update_shift(){
        try{

            if(!\Fuel\Core\Security::check_token()){

                echo "Illegal Operation. Missing token. Hit refresh";

            }else{

                // validate form input
                $val = \Fuel\Core\Validation::forge("edit_shift");

                // validation rules
                $val->add_field("shift_id", "Shift Id", "required|valid_string[numeric]");
                $val->add_field("shift_name", "Shift Name", "required");
                $val->add_field("work_days", "Working Days", "required");
                $val->add_field("day_off", "Day Off", "required");

                $val->add_field("s_hr", "Start Shift Hour", "required|valid_string[numeric]");
                $val->add_field("s_min", "Start Shift Minute", "required|valid_string[numeric]");
                $val->add_field("s_sec", "Start Shift Second", "required|valid_string[numeric]");

                $val->add_field("e_hr", "End Shift Hour", "required|valid_string[numeric]");
                $val->add_field("e_min", "End Shift Minute", "required|valid_string[numeric]");
                $val->add_field("e_sec", "End Shift Second", "required|valid_string[numeric]");

                if(!$val->run()){


                    $errors = $val->error_message();

                    $msg = "";
                    foreach ($errors as $key => $error){
                        $msg .= "{$error}. ";
                    }

                    echo $msg;

                }else{

                    // gather inputs
                    $shift_id = \Fuel\Core\Input::post("shift_id");
                    $shift_name = \Fuel\Core\Input::post("shift_name");
                    $work_days  = \Fuel\Core\Input::post("work_days");
                    $day_off    = \Fuel\Core\Input::post("day_off");

                    $s_hr  = \Fuel\Core\Input::post("s_hr");
                    $s_min = \Fuel\Core\Input::post("s_min");
                    $s_sec = \Fuel\Core\Input::post("s_sec");

                    $e_hr  = \Fuel\Core\Input::post("e_hr");
                    $e_min = \Fuel\Core\Input::post("e_min");
                    $e_sec = \Fuel\Core\Input::post("e_sec");

                    // no check if work days and day off string have unwanted characters
                    // this is possible if the user tries to manipulate the input using a
                    // browsers developer tools
                    $wd = explode(",", $work_days);
                    $do = explode(",", $day_off);

                    // permitted strings or days()
                    $d = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
                    // loop through the days and check its value if it is not permitted remove
                    foreach ($wd as $key => $day) {
                        $dd = strtoupper(trim($day));
                        if(!in_array($dd, $d)){
                            unset($wd[$key]);
                        }
                    }
                    foreach ($do as $day){
                        $dd = strtoupper(trim($day));
                        if(!in_array($dd, $d)){
                            $do = array_diff($do, array($dd));
                        }
                    }
                    // check if there are duplicate days from
                    // wd and do;
                    foreach ($wd as $key => $day) {
                        if(in_array($day, $do)){
                            echo "Duplicate days, Working Days has the similar days on Day Off days!";
                            break;
                        }
                    }


                    $work_days   = implode(",", $wd);
                    $day_off     = implode(",", $do);
                    $start_shift = $s_hr.":".$s_min.":".$s_sec;
                    $end_shift   = $e_hr.":".$e_min.":".$e_sec;

                    $record = Model_Workschedule::find($shift_id);

                    if(count($record) > 0){

                        $record->shift_name  = $shift_name;
                        $record->work_days   = $work_days;
                        $record->day_off     = $day_off;
                        $record->start_shift = $start_shift;
                        $record->end_shift   = $end_shift;

                        $result = $record->save();

                        if($result){

                            $record_info = [
                                "shift_id"    => $shift_id,
                                "shift_name"  => $shift_name,
                                "work_days"   => $work_days,
                                "day_off"     => $day_off,
                                "start_shift" => $start_shift,
                                "end_shift"   => $end_shift
                            ];

                            echo json_encode($record_info);

                        }else {
                           echo "Failed to save shift information";
                        }

                    }else {

                        echo "No shift record with this id";

                    }
                }
            }

        }catch (Exception $e) {
            $e->getMessage();
        }
    }

    public function post_delete_shift(){
        try{

            if(!\Fuel\Core\Security::check_token()){

                echo "Illegal Operation. Missing token. Hit refresh";

            }else{

                // validate form input
                $val = \Fuel\Core\Validation::forge("delete_shift");

                // validation rules
                $val->add_field("shift_id", "Shift Id", "required|valid_string[numeric]");

                if(!$val->run()){


                    $errors = $val->error_message();

                    $msg = "";
                    foreach ($errors as $key => $error){
                        $msg .= "{$error}. ";
                    }

                    echo $msg;

                }else{

                    // gather inputs
                    $shift_id = \Fuel\Core\Input::post("shift_id");

                    $record = Model_Workschedule::find($shift_id);

                    if(count($record) > 0){

                        $result = $record->save();

                        if($result){

                            $record_info = [
                                "shift_id"    => $shift_id,
                            ];

                            echo json_encode($record_info);

                        }else {
                            echo "Failed to delete shift information";
                        }

                    }else {

                        echo "No shift record with this id";

                    }
                }
            }

        }catch (Exception $e) {
            $e->getMessage();
        }
    }
}