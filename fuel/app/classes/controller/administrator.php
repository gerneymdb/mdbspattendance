<?php
// form input validation
use \Fuel\Core\Validation as Validation;
// csrf
use \Fuel\Core\Security as Security;
// session class
use Fuel\Core\Session as Session;
// authentication
use Auth\Auth as Auth;

class Controller_Administrator extends \Fuel\Core\Controller_Template {

    public $template = 'admin_template';

    public function action_index() {
        \Fuel\Core\Response::redirect("administrator/system_settings");
    }

    /**
     *  System Settings Page
     */
    public function action_system_settings() {
        // redirect if not login
        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // has access to system settings?
        if(!Auth::has_access("system_settings.[create, read, update, delete]")){
            \Fuel\Core\Response::redirect("messages");
        }

        // used to make determine which menu to put a class active
        Session::set_flash("page", "system settings");

        $data["default_setting"] = $this->fetch_settings();
        $data['leave_settings']  = $this->fetch_leave_settings();

        $this->template->title = "System Settings";
        $this->template->content = \Fuel\Core\View::forge("admin/system_settings", $data);
    }

    public function action_new_admin(){
        try{

            // redirect if not login
            if(!Model_Login::is_user_logged_session_valid()){
                \Fuel\Core\Response::redirect("login", "refresh");
            }

            // has access to system settings?
            if(!Auth::has_access("new_admin.[create, read, update, delete]")){
                \Fuel\Core\Response::redirect("messages");
            }

            // used to make determine which menu to put a class active
            Session::set_flash("page", "new admin");

            $this->template->title = "System Settings";
            $this->template->content = \Fuel\Core\View::forge("admin/new_admin");

        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Work Schedule page
     */
    public function action_work_schedule(){

        // redirect if not login
        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // has access to system settings?
        if(!Auth::has_access("work_schedule.[create, read, update, delete]")){
            \Fuel\Core\Response::redirect("messages");
        }

        // used to make determine which menu to put a class active
        Session::set_flash("page", "work schedule");

        $data['shifts'] = $this->fetch_shifts();

        $this->template->title = "Work Schedule";
        $this->template->content = \Fuel\Core\View::forge("admin/work_schedule", $data);

    }

    /**
     * Holidays Page
     */
    public function action_leave_application() {

        // redirect if not login
        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // has access to system settings?
        if(!Auth::has_access("leave_applications.[create, read, update, delete]")){
            \Fuel\Core\Response::redirect("messages");
        }

        // used to make determine which menu to put a class active
        Session::set_flash("page", "leave applications");

        // fetch leave applications of employees
        $data["leaves"] = $this->fetch_leaves();

        // fetch employees
        $data["employees"] = $this->fetch_employees_fullname();

        $this->template->title = "Leave Application";
        $this->template->content = \Fuel\Core\View::forge("admin/leave_application", $data);

    }

    /**
     * Holidays Page
     */
    public function action_holidays($year = null){

        // redirect if not login
        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // has access to system settings?
        if(!Auth::has_access("holidays.[create, read, update, delete]")){
            \Fuel\Core\Response::redirect("messages");
        }

        // used to make determine which menu to put a class active
        Session::set_flash("page", "holidays");

        // the year
        $data['year'] = $year;
        // get holidays
        $data["holidays"] = $this->fetch_holidays($year);

        $this->template->title = "Holidays";
        $this->template->content = \Fuel\Core\View::forge("admin/holidays", $data);

    }

    /**
     * Manage Employees Page
     */
    public function action_manage_employees(){

        // redirect if not login
        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // has access to system settings?
        if(!Auth::has_access("manage_employees.[create, read, update, delete]")){
            \Fuel\Core\Response::redirect("messages");
        }

        $data['employees'] = $this->fetch_employees();

        $data['user_creds'] = $this->fetch_user_credentials();

        $data["shifts"] = $this->fetch_shifts();

        // used to make determine which menu to put a class active
        Session::set_flash("page", "employees");

        $this->template->title = "Manage Employees";
        $this->template->content = \Fuel\Core\View::forge("admin/manage_employees", $data);

    }

    /**
     * Mange Attendance Page
     */
    public function action_manage_attendance($data_record = null) {

        // redirect if not login
        if(!Model_Login::is_user_logged_session_valid()){
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // has access to system settings?
        if(!Auth::has_access("manage_attendance.[create, read, update, delete]")){
            \Fuel\Core\Response::redirect("messages");
        }

        // used to make determine which menu to put a class active
        Session::set_flash("page", "attendance");


        // attendance record of all employees
        $data['attendance'] = $data_record;

        $data['employee_names'] = $this->fetch_employees_fullname();

        // arrange employee full info
        $data['employees'] = $this->fetch_rearrange_employee_info();

        // the shifts
        $data['shifts'] = $this->fetch_shifts();

        // $regular holiday
        $data['regular_holiday'] = $this->get_regular_holiday($data_record['year']);

        // special holiday
        $data['special_holiday'] = $this->get_special_holiday($data_record['year']);

        $this->template->title = "Attendance";
        $this->template->content = \Fuel\Core\View::forge("admin/manage_attendance", $data);

    }

    /**
     * Process for setting a new holiday
     */
    public function post_set_holiday(){
        try {

            // check if token exist
            if(!Security::check_token()){
                // csrf token is no present
                $msg[] = "Illegal Operation";
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/holidays", "refresh");
            }


            $val = Validation::forge("leave_application");

            $val->add_field("holiday_name", "Holiday Name", "required");
            $val->add_field("start_day", "Start of Holiday", "required");
            $val->add_field("end_day", "End of Holiday", "required");
            $val->add_field("type", "Holiday Type", "required");
            $val->add_field("description", "Holiday Description", "required|max_length[500]");
            $val->add_field("with_work", "Holiday with work", "required");

            if(!$val->run()){
                $msg = $val->error_message();
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/holidays", "refresh");
            }

            $holiday_name = \Fuel\Core\Input::post("holiday_name");
            $start_day = \Fuel\Core\Input::post("start_day");
            $end_day = \Fuel\Core\Input::post("end_day");
            $type = \Fuel\Core\Input::post("type");
            $description = \Fuel\Core\Input::post("description");
            $with_work = \Fuel\Core\Input::post("with_work");

            $new_record = Model_Holidays::forge();
            $new_record->holiday_name = $holiday_name;
            $new_record->start_day = $start_day . " 00:00:00";
            $new_record->end_day = $end_day . " 23:59:59";
            $new_record->type = $type;
            $new_record->description = $description;
            $new_record->with_work = $with_work;

            $result = $new_record->save();

            if($result){
                // successfull
                $msg[] = "New holiday is set";
                Session::set_flash("smsg", $msg);
                \Fuel\Core\Response::redirect("administrator/holidays", "refresh");
            }else {
                // failed
                $msg[] = "Failed to set new holiday";
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/holidays", "refresh");
            }

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    /**
     *  get attendance record
     */
    public function post_fetch_attendance(){
        try{

            // check if token exist
            if(!\Fuel\Core\Security::check_token()){
                // csrf token is no present
                $msg[] = "Illegal Operation";
                \Fuel\Core\Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/manage_attendance", "refresh");
            }

            $val = Validation::forge("manage_attendance");

            $val->add_field("month", "Months", "required");
            $val->add_field("year", "Year", "required");

            if(!$val->run()){
                $msg = $val->error_message();
                \Fuel\Core\Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/manage_attendance", "refresh");
            }

            $month = \Fuel\Core\Input::post("month");
            $year  = \Fuel\Core\Input::post("year");

            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $start = $year."-".$month."-01 00:00:00";
            $end   = $year."-".$month."-".$days." 23:59:59";

            $result = Model_Attendance::find("all", array(
                "where" => array(
                    array("timein", ">=", $start),
                    array("timein", "<=", $end)
                )
            ));

            $attendance = [
                "genreynalda" => array(
                    "2017-12-01" => array("timein"=>"09:00", "timeout" => "18:00", "status" => "Present"),
                    "2017-12-02" => array("timein"=>"09:00", "timeout" => "18:00", "status" => "Present"),
                    "2017-12-03" => array("timein"=>"09:00", "timeout" => "18:00", "status" => "Present")
                ),
            ];

            $attendance = []; // attendance[userid][date]

            foreach ($result as $employee){
                // if employee userid is not yet in attendance add to
                if(!array_key_exists($employee->userid, $attendance)){

                    $attendance[$employee->userid] = [];
                    foreach ($result as $record) {

                        if($record->userid == $employee->userid){

                            $raw_date = $record->timein;
                            $raw_date = explode(" ", $raw_date);
                            $date = $raw_date[0];

                            $attendance[$employee->userid][$date] = array(
                                "attendance_id" => $record->attendance_id,
                                "timein"        => $record->timein,
                                "timeout"       => $record->timeout,
                                "status"        => $record->status
                            );

                        }//

                    }//

                }//
            }

            $attendance["month"] = $month;
            $attendance["year"]  = $year;

            $this->action_manage_attendance($attendance);

        }catch (Exception $e){
            die($e->getMessage());
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
     * Process for adding a new employee
     */
    public function post_add_employee(){

        try {

            // csrf token is missing
            // check if token exist
            if(!Security::check_token()){
                // csrf token is no present
                $msg[] = "Illegal Operation";
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/manage_employees", "refresh");
            }

            // validate form input
            $val = Validation::forge("add_employee");

            // validation rules
            $val->add_field("fname", "First Name", "required");
            $val->add_field("lname", "Last Name", "required");
            $val->add_field("shift_id", "Shift", "required");
            $val->add_field("userid", "User ID", "required");
            $val->add_field("email", "Email Address", "required|valid_email");

            if(!$val->run()){
                $msg = $val->error_message();
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/manage_employees", "refresh");
            }

            // gather all input
            $fname     = \Fuel\Core\Input::post("fname");
            $mname     = \Fuel\Core\Input::post("mname");
            $lname     = \Fuel\Core\Input::post("lname");
            $shift_id  = \Fuel\Core\Input::post("shift_id");
            $userid    = \Fuel\Core\Input::post("userid");
            $email     = \Fuel\Core\Input::post("email");

            // get the default password
            $settings = Model_Settings::find("all");
            $default_pwd = "";
            foreach ($settings as $setting) {
                $default_pwd = $setting->default_pwd;
            }

            $new_emp = Model_Employee::forge();
            $new_emp->userid   = $userid;
            $new_emp->fname    = $fname;
            $new_emp->mname    = $mname;
            $new_emp->lname    = $lname;
            $new_emp->shift_id = $shift_id;
            $result1 = $new_emp->save();

            $p_fields = array(
                "fname" => $fname,
                "mname" => $mname,
                "lname" => $lname,
                "poisition" => "Employee"
            );

            $result2 = Auth::create_user($userid, $default_pwd, $email, $group = 1, $profile_fields = $p_fields);

            if($result1 && ($result2 != false)){

                $msg[] = "New employee successfully added.!";
                Session::set_flash("smsg", $msg);
                \Fuel\Core\Response::redirect("administrator/manage_employees", "refresh");

            }else {

                $msg[] = "Something went wrong in adding new employee!";
                Session::set_flash("smsg", $msg);
                \Fuel\Core\Response::redirect("administrator/manage_employees", "refresh");

            }

        }catch (Exception $e){
            die($e->getMessage());
        }

    }

    public function post_add_admin(){
        try{

            // csrf token is missing
            // check if token exist
            if(!Security::check_token()){
                // csrf token is no present
                $msg[] = "Illegal Operation";
                Session::set_flash("a_msg", $msg);
                \Fuel\Core\Response::redirect("administrator/new_admin", "refresh");
            }

            // validate form input
            $val = Validation::forge("add_new_admin");

            // validation rules
            $val->add_field("fname", "First Name", "required");
            $val->add_field("lname", "Last Name", "required");
            $val->add_field("username", "Username", "required");
            $val->add_field("email", "Email Address", "required|valid_email");

            if(!$val->run()){
                $msg = $val->error_message();
                Session::set_flash("a_msg", $msg);
                \Fuel\Core\Response::redirect("administrator/new_admin", "refresh");
            }

            // gather all input
            $fname     = \Fuel\Core\Input::post("fname");
            $mname     = \Fuel\Core\Input::post("mname");
            $lname     = \Fuel\Core\Input::post("lname");
            $userid    = \Fuel\Core\Input::post("username");
            $email     = \Fuel\Core\Input::post("email");

            // get the default password
            $settings = Model_Settings::find("all");
            $default_pwd = "";
            foreach ($settings as $setting) {
                $default_pwd = $setting->default_pwd;
            }

            $p_fields = array(
                "fname" => $fname,
                "mname" => $mname,
                "lname" => $lname,
                "poisition" => "Administrator"
            );

            $result2 = Auth::create_user($userid, $default_pwd, $email, $group = 2, $profile_fields = $p_fields);

            if($result2 != false){

                $msg[] = "New admin successfully added.!";
                Session::set_flash("a_smsg", $msg);
                \Fuel\Core\Response::redirect("administrator/new_admin", "refresh");

            }else {

                $msg[] = "Something went wrong in adding new administrator!";
                Session::set_flash("a_msg", $msg);
                \Fuel\Core\Response::redirect("administrator/new_admin", "refresh");

            }

        }catch (Exception $e){

            die($e->getMessage());

        }
    }

    /**
     *  Process when editing the system setting
     */
    public function post_edit_data_settings(){
        try {

            if(!Security::check_token()){

                $msg[] = "Illegal Operation!";
                Session::set_flash("setting_f_msg", $msg);
                \Fuel\Core\Response::redirect("administrator/system_settings", "refresh");

            }

            // validate form input
            $val = Validation::forge("add_employee");

            // validation rules
            $val->add_field("default_pwd", "Default password", "required");
            $val->add_field("reset_pwd_after", "Reset password after", "required|valid_string[numeric]");
            $val->add_field("session_timeout_after", "Session timeout after", "required|valid_string[numeric]");

            if(!$val->run()){
                $msg = $val->error_message();
                Session::set_flash("setting_f_msg", $msg);
                \Fuel\Core\Response::redirect("administrator/system_settings", "refresh");
            }


            $id = \Fuel\Core\Input::post("id");
            $default_pwd = \Fuel\Core\Input::post("default_pwd");
            $reset_pwd_after = \Fuel\Core\Input::post("reset_pwd_after");
            $session_timeout_after = \Fuel\Core\Input::post("session_timeout_after");

            $setting_record = Model_Settings::find($id);

            $setting_record->default_pwd = $default_pwd;
            $setting_record->reset_pwd_after = $reset_pwd_after*84600;
            $setting_record->session_timeout_after = $session_timeout_after*3600;

            $result = $setting_record->save();

            if($result){

                $msg[] = "Settings information saved!";
                Session::set_flash("setting_s_msg", $msg);
                \Fuel\Core\Response::redirect("administrator/system_settings", "refresh");

            }else {

                if(!Security::check_token()){

                    $msg[] = "Illegal Operation!";
                    Session::set_flash("setting_f_msg", $msg);
                    \Fuel\Core\Response::redirect("administrator/system_settings", "refresh");

                }

                // validate form input
                $val = Validation::forge("add_employee");

                // validation rules
                $val->add_field("default_pwd", "Default password", "required");
                $val->add_field("reset_pwd_after", "Reset password after", "required|valid_string[numeric]");
                $val->add_field("session_timeout_after", "Session timeout after", "required|valid_string[numeric]");

                if(!$val->run()){
                    $msg = $val->error_message();
                    Session::set_flash("setting_f_msg", $msg);
                    \Fuel\Core\Response::redirect("administrator/system_settings", "refresh");
                }

            }

        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Process for adding a new leave category
     *
     */
    public function post_add_leave_category() {
        try {

            if(!Security::check_token()){

                $msg[] = "Illegal Operation!";
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/system_settings", "refresh");

            }

            // validate form input
            $val = Validation::forge("add_employee");

            // validation rules
            $val->add_field("leave_name", "Leave Name", "required");
            $val->add_field("days_alloted", "Days Alloted", "required|valid_string[numeric]");

            if(!$val->run()){
                $msg = $val->error_message();
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/system_settings", "refresh");
            }

            $leave_name   = \Fuel\Core\Input::post("leave_name");
            $days_alloted = \Fuel\Core\Input::post("days_alloted");

            $record = Model_Leavesettings::forge();
            $record->leave_name   = $leave_name;
            $record->days_alloted = $days_alloted;
            $result = $record->save();

            if($result){
                $msg[] = "New leave category successfully added";
                Session::set_flash("smsg", $msg);
                \Fuel\Core\Response::redirect("administrator/system_settings", "refresh");
            }else {
                $msg[] = "Failed to add new category. Something went wrong. Please contact your system administrator";
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("administrator/system_settings", "refresh");
            }


        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    public function post_add_shifts(){
        try{

            if(!Security::check_token()){

                $msg[] = "Illegal Operation!";
                Session::set_flash("a_msg", $msg);
                \Fuel\Core\Response::redirect("administrator/work_schedule", "refresh");

            }

            // validate form input
            $val = Validation::forge("add_shift");

            // validation rules
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
                $msg = $val->error_message();
                Session::set_flash("a_msg", $msg);
                \Fuel\Core\Response::redirect("administrator/work_schedule", "refresh");
            }

            // gather inputs
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
                    $msg[] = "Duplicate days, Working Days has the similar days on Day Off days!";
                    Session::set_flash("a_msg", $msg);
                    \Fuel\Core\Response::redirect("administrator/work_schedule", "refresh");
                }
            }

            $work_days   = implode(",", $wd);
            $day_off     = implode(",", $do);
            $start_shift = $s_hr.":".$s_min.":".$s_sec;
            $end_shift   = $e_hr.":".$e_min.":".$e_sec;

            $record = Model_Workschedule::forge();
            $record->shift_name  = $shift_name;
            $record->work_days   = $work_days;
            $record->day_off     = $day_off;
            $record->start_shift = $start_shift;
            $record->end_shift   = $end_shift;

            $result = $record->save();

            if($result){
                $msg[] = "New Shift has been added!";
                Session::set_flash("a_smsg", $msg);
                \Fuel\Core\Response::redirect("administrator/work_schedule", "refresh");
            }else {
                $msg[] = "Failed to add the new shift";
                Session::set_flash("a_msg", $msg);
                \Fuel\Core\Response::redirect("administrator/work_schedule", "refresh");
            }

        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    /**d
     * Fetch ALl Leave Information
     *
     * @return \Orm\Model|\Orm\Model[]|string
     */
    private function fetch_leaves() {

        try {

            $result  = Model_Leave::find("all", array(
                "order_by" => array("date_filed" => "desc")
            ));

            return $result;

        }catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * Fetch All Employees Information
     *
     * @return array|string
     */
    private function fetch_employees_fullname(){
        try{

            $employees = [];

            $result = Model_Employee::find("all");

            if(count($result) > 0){

                foreach ($result as $employee) {
                    $employees[$employee->userid] = $employee->fname . " " . $employee->mname . " " . $employee->lname;
                }

            }

            return $employees;

        }catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Retrieve all employee and their information
     *
     * @return \Orm\Model|\Orm\Model[]
     */
    private function fetch_employees() {
        try{

            $emp_records = Model_Employee::find("all");

            return $emp_records;

        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Retrieves User's credentials
     *
     * @return array
     *
     */
    private function fetch_user_credentials(){
        try {

            $users = [];

            $result = Model_Login::find("all");

            foreach ($result as $info) {
                $users[$info->username] = $info;
            }

            return $users;

        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    /**
     * Retrieves shifts
     *
     * @return array
     */
    private function fetch_shifts(){
        try{

            $shifts = [];

            $result = Model_Workschedule::find("all");

            foreach ($result as $info){
                $shifts[$info->shift_id] = $info;
            }

            return $shifts;

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    /**
     * fetch holiday in a year
     *
     * @param null $year
     * @return \Orm\Model|\Orm\Model[]
     */
    private function fetch_holidays($year = null){
        try {

            $year = is_numeric($year) && ($year != null) ? $year : strftime("%Y", time());

            // set start of the year
            $start_of_year = $year . "-01-31 00:00:00";
            $end_of_year   = $year . "-12-30 23:59:59";

            $result = Model_Holidays::find("all", array(
                "where" => array(
                    array("start_day", ">=", $start_of_year),
                    array("start_day", "<=", $end_of_year)
                ),
                "order_by" => array("start_day" => "desc")
            ));


            return $result;
        }catch(Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Get Settings
     *
     * @return mixed|\Orm\Model
     */
    private function fetch_settings(){
        try{

            $result = Model_Settings::find("all");

            return array_shift($result);

        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    private function fetch_leave_settings(){
        try{

            $result = Model_Leavesettings::find('all');

            return $result;

        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    private function fetch_rearrange_employee_info(){

        try{

            // list of employees
            $employees = [];

            // fetch employees from the database
            $result = Model_Employee::find("all");

            if(count($result) > 0){

                foreach ($result as $emp) {
                    $employees[$emp->userid] = $emp;
                }

            }

            return $employees;
        }catch(Exception $e){
            die($e->getMessage());
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

}