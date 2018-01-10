<?php
// form input validation
use \Fuel\Core\Validation as Validation;
// csrf
use \Fuel\Core\Security as Security;
// session class
use Fuel\Core\Session as Session;
// authentication
use Auth\Auth as Auth;
// input
use Fuel\Core\Input as Input;
// upload class
use Fuel\Core\Upload as Upload;

class Controller_User extends \Fuel\Core\Controller_Template {

    public $template = 'users_template';

    public function after($response){
        parent::after($response);

        $response = parent::after($response);
        $response = $response->set_header('Cache-Control', 'no-store, no-cache, must-revalidate,  max-age=0');
        $response = $response->set_header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $response = $response->set_header('Pragma', 'no-cache');
        return $response;
    }

    public function action_index() {
        // redirect to attendance
        \Fuel\Core\Response::redirect("user/attendance");
    }

    /**
     * Load Time In Page
     */
    public function action_attendance(){

        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // check if user has access to attendance
        if(!Auth::has_access("attendance.[read]")){
            \Fuel\Core\Response::redirect("unauthorize", "refresh");
        }

        $data = [
            'rh' => null, // regular holiday
            'sh' => null, // special holiday
            'l'  => null, // leave
            'do' => null,  // day off
            'timed_in' => null, // already time in
            'done_today' => null, // today's work is over
            'info' => null, // employees info
            'timein' => null,// employees timein value
            'timeout' => null, // employees timeout value
            'shift_sched' => null, // employees shift schedule
            'total_time' => null // total hours worked
        ];

        // check for regular holidays
        $rh = Model_Holidays::is_regular_holiday();
        $data['rh'] = $rh == false ? false : $rh;

        // check for special holiday
        $sh = Model_Holidays::is_special_holiday();
        $data['sh'] = $sh == false ? false : $sh;

        // check for leave
        $l = Model_Leave::is_leave();
        $data['l'] = $l == false ? false : $l;

        // check for day off
        $do = Model_Employee::is_day_off();
        $data['do'] = $do == false ? false : $do;

        // already time in
        $timed_in = $this->is_already_timein();
        $data['timed_in'] = $timed_in == true ? true : false;

        // is work already done today
        $done_today = $this->is_todays_work_over();
        $data['done_today'] = $done_today == true ? true : false;

        // time in value
        $timein = $this->timein();
        $data['timein'] = $timein;

        // time out value
        $timeout = $this->timeout();
        $data['timeout'] = $timeout;

        // shift schedule of employee
        $data['shift_sched'] = $this->get_schedule();

        // total hours
        $data['total_time'] = $this->total_time();

        // get employee info
        $userid = Session::get("username");
        $result = Model_Employee::find("all", array(
            "where" => array(
                array("userid", "=", $userid)
            )
        ));

        if(count($result) > 0){
            $data['info'] = array_shift($result);
        }

        $this->template->title = "Employees";
        $this->template->content = \Fuel\Core\View::forge("users/attendance", $data);
    }

    /**
     * Load Attendance History Page
     *
     */
    public function action_history($year = null) {

        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // check if user has access to attendance_history
        if(!Auth::has_access("attendance_history.[read]")){
            \Fuel\Core\Response::redirect("messages");
        }

        // get year set it to current year if year is null
        $data['year'] = $year = ($year == null || !is_numeric($year)) ? strftime("%Y", time()) : $year;

        //months
        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        $userid = Session::get("username");

        /**
         * array(
         *   "January" => array(
                    "1" => $object->attendance,
         *    ),
         * )
         */

        // attendance data
        $attendance = [];
        // calculate no. of days in a month in the year provided
        for($x = 1; $x <= 12; $x++){


            $months_no_of_days = cal_days_in_month(CAL_GREGORIAN, $x, $year);
            // loop months_no_of_days and get
            // the attendance of the employee
            for ($a = 1; $a <= $months_no_of_days; $a++){

                // get attendance data using
                // $d = day, $m = month;
                $d = ($a < 10) ? "0".$a : $a;
                $m = ($x < 10) ? "0".$x : $x;

                $today = $year."-".$m."-".$d." 00:00:00";
                $end_of_the_day = $year."-".$m."-".$d." 23:59:59";

                // get attendance data
                $result = Model_Attendance::find("all", array(
                    "where" => array(
                        array("userid", "=", $userid),
                        array("timein", ">=", $today),
                        array("timein", "<", $end_of_the_day)
                    )
                ));

                // put to attendance variable the attendance for a year
                $attendance[$months[$x-1]][$d] = (count($result) > 0) ? array_shift($result) : false;
            }

        }
        // shift schedule of employee
        $data['shift_sched'] = $this->get_schedule();

        // employees attendance
        $data['attendance'] = $attendance;

        // employees leave record
        $data['leave_record'] = $this->get_leave($year);

        // regular holiday
        $data['regular_holiday'] = $this->get_regular_holiday($year);

        // special holiday
        $data['special_holiday'] = $this->get_special_holiday($year);

        $this->template->title = "Employees";
        $this->template->content = \Fuel\Core\View::forge("users/attendance_history", $data);

    }

    /**
     * Load Employee Leave Application Page
     */
    public function action_leave_application(){
        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login");
        }

        // check if user has access to employee leave.
        if(!Auth::has_access("leave.[read]")){
            \Fuel\Core\Response::redirect("unauthorize", "refresh");
        }

        // get pending leave
        $data['pending_leave'] = $this->get_latest_leave();

        // get leave settings
        $leave_settings = $this->all_leave_settings();

        $leave_info = [];

        foreach ($leave_settings as $leave){
            $leave_name = trim($leave->leave_name);

            $days_remaining = $this->get_leave_days_left($leave_name);
            $leave_info[$leave_name] = ['days_allotted' => $leave->days_alloted, 'days_remaining' => $days_remaining];
        }

        $data['leave_info'] = $leave_info;

        $this->template->title = "Employees";
        $this->template->content = \Fuel\Core\View::forge("users/leave", $data);
    }

    /**
     * @param null $year
     */
    public function action_leave_history($year = null){
        // redirect if not login
        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // check if user has access to attendance_history
        if(!Auth::has_access("leave_history.[read]")){
            \Fuel\Core\Response::redirect("messages");
        }

        // get year set it to current year if year is null
        $data['year'] = $year = ($year == null || !is_numeric($year)) ? strftime("%Y", time()) : $year;


        $data['leave_history'] = $this->fetch_leave_history($year);

        $this->template->title = "Employee";
        $this->template->content = \Fuel\Core\View::forge("users/leave_history", $data);

    }

    /**
     *  Time in process
     */
    public function post_timein(){
        $msg = [];

        // get the current time
        $time_in = strftime("%Y-%m-%d %H:%M:%S", time());
        // get the current logged in user;
        $userid = Session::get("username");

        // check if csrf activated
        if(Security::check_token()){

            $attendance = Model_Attendance::forge();
            $attendance->userid  = $userid;
            $attendance->timein  = $time_in;
            $attendance->timeout = NULL;
            $attendance->status  = "";

            // save changes
            $saved = $attendance->save();

            if($saved){
                // changes is saved
                // set  msg
                $msg[] = "Time in successfull";
                Session::set_flash("smsg", $msg);
                \Fuel\Core\Response::redirect("user/attendance");

            }else {

                // failed to time in
                $msg[] = "Time in failed";
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("user/attendance");

            }

        }else {
            // csrf token is no present
            $msg[] = "Illegal Operation";
            Session::set_flash("msg", $msg);
            \Fuel\Core\Response::redirect("user/attendance");
        }
    }

    /**
     * Time out process
     */
    public function post_timeout(){

        $time_out = strftime("%Y-%m-%d %H:%M:%S", time());
        $userid = Session::get("username");

        if(Security::check_token()){


            // get user info
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
                    array("timein", ">=", $today),
                    array("timein", "<", $end_of_the_day)
                )
            ));

            //get user info
            $user = array_shift($result);
            // update timeout and status column
            $user->timeout = $time_out;
            $user->status  = "Present";
            //saved chnages
            $saved = $user->save();

            if($saved){

                // changes is saved
                // set  msg
                $msg[] = "Time out successfull";
                Session::set_flash("smsg", $msg);
                \Fuel\Core\Response::redirect("user/attendance");

            }else {

                // failed to time out
                $msg[] = "Time in failed";
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("user/attendance");

            }

        }else {

            // csrf token is no present
            $msg[] = "Illegal Operation";
            Session::set_flash("msg", $msg);
            \Fuel\Core\Response::redirect("user/attendance");

        }
    }

    /**
     * Process for applying for leave
     */
    public function post_leave_apply(){
        $userid     = Session::get("username");

        $pending_leave = Model_Leave::find("all", array(
            "where" => array(
                array('userid', "=", $userid),
                array('status', "=", "pending")
            )
        ));

        if(count($pending_leave) <= 0){
            // no pending leave
            $on_leave = Model_Leave::is_leave();

            if($on_leave == false){
                // not currently on leave
                $val = Validation::forge("leave_application");

                $val->add_field("leave_cat", "leave category", "required");
                $val->add_field("start_date", "start date", "required|valid_date");
                $val->add_field("end_date", "end date", "required|valid_date");

                // check csrf token
                if(Security::check_token()){

                    // form validation
                    if($val->run()){

                        // fetch data
                        $leave_cat  = Input::post("leave_cat");
                        $start_date = Input::post("start_date");
                        $end_date   = Input::post("end_date");
                        $reason     = Input::post("reason");
                        $status     = "pending";

                        $start_date = $start_date." 00:00:00";
                        $end_date   = $end_date." 23:59:59";

                        // check for remaning leave days
                        $leave_cat = trim($leave_cat);
                        $days_remain = $this->get_leave_days_left($leave_cat);

                        if($days_remain > 0){
                            // has remaining days for this leave

                            // check if date chosen exceeds the remaining day allowed for this type leave
                            $starttime = strtotime($start_date);
                            $endtime   = strtotime($end_date);

                            $days_chosen = $this->dateDiff($start_date, $end_date);

                            if($days_chosen > $days_remain){

                                // no of days chosen by the employee
                                // exceeds the days remaining allowed
                                // on this type of leave
                                // show error msg
                                $msg[] = "The number of days you chose exceeds the number of days remaining for <strong>{$leave_cat}</strong>";
                                Session::set_flash("msg", $msg);
                                \Fuel\Core\Response::redirect("user/leave_application", "refresh");

                            }

                            if($endtime < $starttime){

                                // the employee choose an end date
                                // that is in the past. show error message
                                $msg[] = "The date you choose for the <strong>End Date</strong> is a date of the past. Please choose a future date.";
                                Session::set_flash("msg", $msg);
                                \Fuel\Core\Response::redirect("user/leave_application", "refresh");

                            }

                            // is correctly filed
//                            $this->is_leave_correctly_filed($leave_cat, $start_date, $end_date);

                            // set config for this upload
                            $config = array(
                                'path'        => DOCROOT ."files".DS."leave",
                                'randomize'   => true,
                                'auto_rename' => true
                            );
                            // process config
                            Upload::process($config);

                            if(Upload::is_valid()){
                                // no problem in upload
                                Upload::save();

                            }else{
                                $errors = Upload::get_errors();

                                if(strlen($errors[0]['name']) <= 0){
                                    // no file chosen do nothing
                                }else{
                                    $ermsg = [];
                                    foreach ($errors[0]['errors'] as $error) {
                                        $ermsg[] = $error['message'];
                                    }
                                    $msg = $ermsg;
                                    Session::set_flash("msg", $msg);
                                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");
                                }
                            }

                            $saved_file = Upload::get_files();
                            if(count($saved_file) > 0){
                                // name of the file
                                $attachment = $saved_file[0]['saved_as'];

                                // save to the database with attachments
                                $leave = Model_Leave::forge();
                                $leave->userid       = $userid;
                                $leave->start_leave  = $start_date;
                                $leave->end_leave    = $end_date;
                                $leave->type         = $leave_cat;
                                $leave->reason       = $reason;
                                $leave->status       = $status;
                                $leave->approved_by  = "";
                                $leave->attachments  = $attachment;
                                $leave->date_filed   = strftime("%Y-%m-%d %H:%M:%S", time());
                                $leave->comments     = "";

                                //saved changes
                                if($leave->save()){
                                    // successfull process of leave request
                                    $msg[] = "<strong>{$leave_cat}</strong> request was submitted and is subject for evaluation.";
                                    Session::set_flash("smsg", $msg);
                                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");
                                }else {
                                    // not able to saved
                                    $msg[] = "Unable to process leave request";
                                    Session::set_flash("msg", $msg);
                                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");
                                }
                            }else {

                                // save to the database with attachments
                                $leave = Model_Leave::forge();
                                $leave->userid       = $userid;
                                $leave->start_leave  = $start_date;
                                $leave->end_leave    = $end_date;
                                $leave->type         = $leave_cat;
                                $leave->reason       = $reason;
                                $leave->status       = $status;
                                $leave->approved_by  = "";
                                $leave->attachments  = "";
                                $leave->date_filed   = strftime("%Y-%m-%d %H:%M:%S", time());
                                $leave->comments     = "";

                                // save changes
                                if($leave->save()){
                                    // successfull process of leave request
                                    $msg[] = "<strong>{$leave_cat}</strong> request was submitted and is subject for evaluation.";
                                    Session::set_flash("smsg", $msg);
                                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");
                                }else {
                                    // not able to saved
                                    $msg[] = "Unable to process leave request";
                                    Session::set_flash("msg", $msg);
                                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");
                                }

                            }

                        }else {

                            // employee has used up all the days allotted for this leave
                            $msg[] = "You have already consume the number of days given for ".$leave_cat." in a year";
                            Session::set_flash("msg", $msg);
                            \Fuel\Core\Response::redirect("user/leave_application");

                        }

                    }else {
                        $msg = $val->error_message();
                        Session::set_flash("msg", $msg);
                        \Fuel\Core\Response::redirect("user/leave_application", "refresh");
                    }


                }else{

                    // csrf token is no present
                    $msg[] = "Illegal Operation";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("user/leave_application");
                }

            }else {

                // on leave
                $msg[] = "You're currently on leave.";
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("user/leave_application", "refresh");
            }

        }else {

            // you have a pending leave
            $msg[] = "You currently have a pending leave request.";
            Session::set_flash("msg", $msg);
            \Fuel\Core\Response::redirect("user/leave_application", "refresh");

       }
    }
    /**
     * Delete leave process
     */
    public function post_leave_delete(){

        if(Security::check_token()){

            $val = Validation::forge("leave_delete");
            $val->add_field("leave_id", "leave id field", "required");
            if($val->run()){

                $leave_id = Input::post("leave_id");

                $result = Model_Leave::find($leave_id);
                if($result->delete()){

                    // successfull cancel of leave
                    $msg[] = "Leave request has been <strong>cancelled</strong>.!!";
                    Session::set_flash("smsg", $msg);
                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");

                }else{
                    $msg[] = "Unable to cancel leave.";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("user/leave_application");
                }

            }else {
                $msg = $val->error_message();
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("user/leave_application", "refresh");
            }

        }else{
            // csrf token is no present
            $msg[] = "Illegal Operation";
            Session::set_flash("msg", $msg);
            \Fuel\Core\Response::redirect("user/leave_application");
        }

    }

    /**
     * Checks if already time in
     *
     * @return bool
     */
    public  function is_already_timein(){
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
                array("timein", ">=", $today),
                array("timein", "<", $end_of_the_day)
            )
        ));

        return (count($result) > 0) ? true : false;
    }

    /**
     * @return string
     */
    public function timein(){
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
                array("timein", ">=", $today),
                array("timein", "<", $end_of_the_day)
            )
        ));

        $timein = (count($result) > 0) ? array_shift($result) : "";

        if($timein == "" || empty($timein) || $timein == null){

            return "";

        }else {

            $value = explode(" ", $timein->timein);
            return $value[1];
        }

    }

    /**
     * @return string
     */
    public function timeout(){
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
                array("timein", ">=", $today),
                array("timein", "<", $end_of_the_day)
            )
        ));

        $timeout = (count($result) > 0) ? array_shift($result) : "";

        if($timeout == ""){
            // no record found
            return "";

        }else {
           $value = $timeout->timeout;
           if($value == NULL){
                return "";
           }else {

               $value = explode(" ", $value);
               return $value[count($value) - 1];
           }
        }
    }

    /**
     * Checks if today work is finish.
     *
     * @return bool
     */
    public function is_todays_work_over(){
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
                array("timein", ">=", $today),
                array("timeout", "<", $end_of_the_day),
            )
        ));

        // if count <= 0 then no record in attendance
        // and work have not started, retur false
        if(count($result) <= 0){

            return false;

        }else {
            // get info
            $attendance = array_shift($result);
            return ($attendance->status == "Present") ? true : false;

        }
    }

    /**
     * Get the shift info of a user
     *
     * @return mixed|\Orm\Model|\Orm\Model[]
     */
    private function get_schedule(){
        $userid = Session::get("username");
        $emp_info = Model_Employee::find("all", array(
            "where" => array(
                array("userid", "=", $userid)
            )
        ));
        $emp_info = array_shift($emp_info);
        $shift_id = $emp_info->shift_id;

        $shift_info = Model_Workschedule::find("all", array(
            "where" => array(
                array("shift_id", "=", $shift_id)
            )
        ));

        $shift_info = array_shift($shift_info);

        return $shift_info;
    }

    /**
     * @param null $year
     * @return array|\Orm\Model|\Orm\Model[]
     */
    private function get_leave($year = null){
        try {

            if($year != null){
                $result = Model_Leave::find("all", array(
                    "where" => array(
                        array("userid", "=", "gerneynalda"),
                        array("start_leave", ">=", "{$year}-01-01 00:00:00"),
                        array("start_leave", "<=", "{{$year}}-12-30 23:59:59"),
                        array("status", "=", "approved")
                    )
                ));

                return $result;

            }else {
                return array();
            }

        }catch (Exception $e) {
            die($e->getMessage() ." ". $e->getCode() . " <br />" . $e->getFile() . " <br />");
        }

    }

    /**
     * Fetch all leave data in a year
     *
     * @param null $year
     * @return array|\Orm\Model|\Orm\Model[]
     */
    private function fetch_leave_history ($year = null) {
        try {

            if($year != null){
                $result = Model_Leave::find("all", array(
                    "where" => array(
                        array("userid", "=", "gerneynalda"),
                        array("start_leave", ">=", "{$year}-01-01 00:00:00"),
                        array("start_leave", "<=", "{$year}-12-30 23:59:59")
                    ),
                    "order_by" => array("leave_id" => "desc")
                ));

                return $result;

            }else {
                return array();
            }

        }catch (Exception $e) {
            die($e->getMessage() ." ". $e->getCode() . " <br />" . $e->getFile() . " <br />");
        }
    }

    /**
     * @param null $year
     * @return array|\Orm\Model|\Orm\Model[]
     */
    private function get_regular_holiday($year = null){
        if($year != null){
            $result = Model_Holidays::find("all", array(
                "where" => array(
                    array("type", "=", "Regular Holiday"),
                    array("start_day", ">=", "{$year}-01-01 00:00:00"),
                    array("start_day", "<=", "{$year}-12-31 23:59:59")
                )
            ));

            return $result;
        }else{
            return array();
        }
    }

    /**
     * @param null $year
     * @return array|\Orm\Model|\Orm\Model[]
     */
    private function get_special_holiday($year = null){
        if($year != null){
            $result = Model_Holidays::find("all", array(
                "where" => array(
                    array("type", "=", "Special Holiday"),
                    array("start_day", ">=", "{$year}-01-01 00:00:00"),
                    array("start_day", "<=", "{$year}-12-31 23:59:59")
                )
            ));

            return $result;
        }else{
            return array();
        }
    }

    /**
     * @return \Orm\Model|\Orm\Model[]
     */
    private function get_latest_leave(){
        $userid = Session::get("username");
        $pending_leave = Model_Leave::query()->where('userid', $userid)->order_by("leave_id", "desc")->limit(1)->get();
        return $pending_leave;
    }

    /**
     * @param null $leave_type
     * @return int|mixed
     */
    private function get_leave_days_left($leave_type = null){
        try {

            if($leave_type != null){

                $year = strftime("%Y", time());

                // userid
                $userid = Session::get("username");
                // get leave records of this employee or this leave type
                $leave = Model_Leave::find("all", array(
                    "where" => array(
                        array('userid', "=", $userid),
                        array('status', "=", 'approved'),
                        array('type', '=', $leave_type),
                        array('start_leave', ">=", $year."-01-01 00:00:00"),
                        array('start_leave', "<=", $year."-12-31 23:59:59")
                    )
                ));

                // get the settings of this leave, the allowed number of days for this leave
                $leave_settings = Model_Leavesettings::find("all", array(
                    "where" => array(
                        array("leave_name", "=", $leave_type)
                    )
                ));

                // default settings of this leave
                $settings = array_shift($leave_settings);

                // days spent on this leave
                $no_spent_days = 0;

                // days remaining on this leave
                $remain_days = 0;


                // count how many days the employee has in this kind of leave
                if(count($leave) > 0){

                    // employee have spent his/her leave
                    foreach($leave as $info){

                        $days = $this->dateDiff($info->start_leave, $info->end_leave);

                        $no_spent_days = $no_spent_days + $days;
                    }

                    $remain_days = $settings->days_alloted - $no_spent_days;

                    return $remain_days;

                }else {
                    // user did not spent her leave
                    // therefore return full no of days
                    return $settings->days_alloted;
                }

            }else {
                die("get_leave_days() parameter must not be null ");
            }

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    /**
     * @return array
     */
    private function all_leave_settings(){
        $all_leave = Model_Leavesettings::query()->get();
        return $all_leave;
    }

    /**
     * @param null $start
     * @param null $end
     * @return float|int|string
     */
    private function dateDiff($start = null, $end = null, $weekends = false){
        try {

            // return 0
            if($start == null || $end == null){
                return 0;
            }

            $start = strtotime($start);
            $end   = strtotime($end);

            return ceil((($end - $start) / 86400));

        }catch (Exception $e) {

            return $e->getMessage();

        }
    }

    /**
     * Checks if the application for such leave meets the
     * settings of that leave in the database
     *
     * @param null $leave_type
     * @param null $start_date
     * @param null $end_date
     * @return bool|string
     */
    private function is_leave_correctly_filed($leave_type = null, $start_date = null, $end_date = null){
        try{
            /*
             * The term this kind of leave refers to the
             * leave_type chosen by the user or employee
             */

            if($leave_type == null || $start_date == null || $end_date == null){
                die("null man to ag gin pang butang muh");
            }

            // the settings for this kind of leave
            $result = Model_Leavesettings::query()->where("leave_name", $leave_type)->get();
            $leave_setting = array_shift($result);

            // start date convert to time
            $starttime = strtotime($start_date);
            $endtime = strtotime($end_date);

            $strftime_start = strftime("%Y-%m-%d", time());
            $strftime_end = strftime("%Y-%m-%d", time());

            $startofday = strtotime($strftime_start." 00:00:00");
            $endofday = strtotime($strftime_end." 23:59:59");


            // on
            if($leave_setting->on == "false"){

                if(($starttime >= $startofday) && ($starttime <= $endofday)){
                    $msg[] = "<strong>{$leave_type}</strong> are not allowed to be filed on the day of the start of your leave.";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");
                }
            }

            //before
            if($leave_setting->before == "false"){

                if($starttime < $startofday){

                    $msg[] = "<strong>{$leave_type}</strong> are not allowed to be filed before the start of your leave.";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");

                }

            }else {
                $days =  $this->dateDiff($startofday, $starttime);

                if($days < $leave_setting->days){

                    $msg[] = "<strong>{$leave_type}</strong> must be filed {$leave_setting->days} before the start of your leave.";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");

                }
            }

            // after
            if($leave_setting->after == "false"){

                if($endtime > $startofday){

                    $msg[] = "<strong>{$leave_type}</strong> are not allowed to be submitted after the end of your leave.";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");

                }

            }else{

                $days = $this->dateDiff($startofday, $endtime);

                if($endtime < $startofday && ($days <= $leave_setting->days)){

                    $msg[] = "<strong>{$leave_type}</strong> must be filed within {$leave_setting->days} after the end of your leave.";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("user/leave_application", "refresh");

                }

            }

            return true;

        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    private function total_time(){

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
            return "00 hrs 00 min 00 sec";
        }else {

            // timein time
            $attendance = array_shift($result);

            // todays time
            $todaystime = strftime("%Y-%m-%d %H:%M:%S", time());

            $datetime1 = new DateTime($attendance->timein);
            $datetime2 = new DateTime($todaystime);

            $time_diff = $datetime1->diff($datetime2);

            return $time_diff->format("%H hrs %I min %S sec");
        }
    }
}