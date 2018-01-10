<?php
// form input validation
use \Fuel\Core\Validation as Validation;
// csrf
use \Fuel\Core\Security as Security;
// session class
use Fuel\Core\Session as Session;
// authentication
use Auth\Auth as Auth;

class Controller_Login extends \Fuel\Core\Controller_Template {

    public $template = 'login_template';

    public function after($response){
        parent::after($response);

        $response = parent::after($response);
        $response = $response->set_header('Cache-Control', 'no-store, no-cache, must-revalidate,  max-age=0');
        $response = $response->set_header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $response = $response->set_header('Pragma', 'no-cache');
        return $response;
    }

    public function action_index() {
        if(Auth::check()){
            \Fuel\Core\Response::redirect("login/redirectUser", "refresh");
        }
        $this->template->title = 'Login Pages';
        $this->template->content = View::forge('login/index');

    }

    public function action_firstLogin() {
        // if someone access this through url manipulation
        // check if it is authenticated
        if(!Model_Login::is_user_logged_session_valid()){
            // no authenticated redirect to login page
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // if authenticated but user tries to access this page
        // through url
        // check if current user is first login
        $userid = Session::get("username");
        if(!$this->is_firstLogin($userid)){
            // if not first time login, redirect to landing page
            \Fuel\Core\Response::redirect("login/redirectUser", "refresh");
        }

        // if first time and authenticated then load first time
        // login form
        $this->template->title = 'Login Pages';
        $this->template->content = View::forge('login/first_time_login');

    }

    public function action_schedulePwdReset() {
        // if someone access this through url manipulation
        // check if it is authenticated
        if(!Model_Login::is_user_logged_session_valid()){
            // not authenticated redirec to login page
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // if authenticated but user tries to access this page through url
        // check if current user has expired password
        $userid = Session::get("username");
        if(!$this->is_pwd_expired($userid)){
            // if not expired redirect to landing page
            \Fuel\Core\Response::redirect("login/redirectUser", "refresh");
        }

        // if expired password and authenticated then load change password form
        $this->template->title = 'Login Pages';
        $this->template->content = View::forge('login/scheduled_pwd_reset');

    }

    /**
     * Redirects users to Employee or Administrator page
     * depending on the group the user belongs.
     *
     * However if the user is banned, he/she gets redirected
     * to the login page
     */
    public function action_redirectUser(){
        if(!Model_Login::is_user_logged_session_valid()){
            // redirect to login page if not logged in or session timeout
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        $userid = Session::get("username");

        // still check if user is firstlogin
        if($this->is_firstLogin($userid)){

            \Fuel\Core\Response::redirect("login/firstLogin");
        }

        // still check if user has expired password
       if($this->is_pwd_expired($userid)) {

            \Fuel\Core\Response::redirect("login/schedulePwdReset");
        }

        // Group ID's
        // -1 are banned users
        // 1 are employee
        // 2 are administrators

        // get the group id of the currently logged in user
        $user_group = Auth::get_groups();

        // if -1
        if($user_group[0][1] == -1){
            // user is banned
            $msg[] = "You are currently banned from the system";
            Session::set_flash("msg", $msg);

            // if banned user is currently logged in, logged him out
            if(Auth::check()){
                Model_Login::logout();
            }
            \Fuel\Core\Response::redirect("login", "refresh");
        }

        // if 1
        if($user_group[0][1] == 1){
            // user is an employee
            \Fuel\Core\Response::redirect("user/attendance", "refresh");
        }

        // if 2
        if($user_group[0][1] == 2){
            // user is an administrator
            \Fuel\Core\Response::redirect("administrator", "refresh");
        }

    }

    /**
     * Logout
     */
    public function action_logout(){
        Model_Login::logout();
        // redirect to login page
        \Fuel\Core\Response::redirect("login", "refresh");
    }

    /**
     * Logged in process
     */
    public function post_employeeLogin(){

        // if already login redirect to landing page
        if(Auth::check()){
            \Fuel\Core\Response::redirect("login/redirectUser", "refresh");
        }
        $val = Validation::forge("login");
        $msg = [];

        // csrf check
        if(Security::check_token()){
            // csrf token is valid

            // set validation rules
            $val->add_field("userid", " ID", "required");
            $val->add_field("password", "Password", "required");
            $val->set_message("required", "Please fill out the :label field.!");

            $userid   = \Fuel\Core\Input::post("userid");
            $password = \Fuel\Core\Input::post("password");

            // to be throttle
            // validate userid and password if empty or not
            if($val->run()){

                // check userid is in database
                if($this->check_user($userid)){

                    // check if user exceeds the limit of login attempt
                    // if user exceeds limit, the possibility brute force attack is happening
                    // returns the delay time before the user can login again
                    $time_delay = $this->throttle_failed_login($userid);

                    if($time_delay <= 0){

                        // logins the user
                        if(Auth::login($userid, $password)){

                            // successfull login
                            if($this->is_firstLogin($userid)){

                                // user's first time login
                                // redirect to set new password
                                $this->clear_failed_login($userid);
                                Session::set("login_session_time", time());
                                \Fuel\Core\Response::redirect("login/firstLogin", "refresh");

                            }elseif($this->is_pwd_expired($userid)) {

                                //succesfull login, but expired pwd
                                // redirec to reset password
                                $this->clear_failed_login($userid);
                                Session::set("login_session_time", time());
                                \Fuel\Core\Response::redirect("login/schedulePwdReset", "refresh");

                            }else {

                                // user logs in successfully
                                $this->clear_failed_login($userid);
                                Session::set("login_session_time", time());
                                \Fuel\Core\Response::redirect("login/redirectUser", "refresh");

                            }

                        }else {

                            // user failed to login
                            // increase login attempt by 1
                            $this->record_failed_login($userid);

                            // incorrect userid and password
                            // redirect to login page
                            $msg[] = "Incorrect userid or password";
                            Session::set_flash("msg", $msg);
                            \Fuel\Core\Response::redirect("login", "refresh");
                        }

                    }else {

                        // prevent user from loging in for a while
                        // user has exceed the limit for login attempts
                        $msg[] = "Too many login attempt. Try again later after.".$time_delay;
                        Session::set_flash("msg", $msg);
                        \Fuel\Core\Response::redirect("login", "refresh");

                    }

                }else {

                    // userid is not in database
                    $msg[] = "Incorrect userid or password";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("login", "refresh");

                }

            }else {

                // fields are empty
                $msg[] = "Please fill out the fields";
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("login", "refresh");
            }

        }else {

            // csrf token is missing
            $msg[] = "Token missing or not valid.";
            Session::set_flash("msg", $msg);
            \Fuel\Core\Response::redirect("login", "refresh");
        }

    }

    /**
     * Change password process for first time
     * logged in users
     */
    public function post_newPassword(){
        $val = Validation::forge("new_password");
        $msg = null;

        // csrf check
        if(Security::check_token()){
            // csrf token is valid

            // set validation rules

            $val->add_field("old_password", "old password", "required");
            $val->add_field("new_password", "new password", "required|min_length[8]");
            $val->add_field("confirm_password", "confirm password", "required|min_length[8]|match_field[new_password]");

            $userid       = \Fuel\Core\Input::post("username");
            $old_password = \Fuel\Core\Input::post("old_password");
            $new_password = \Fuel\Core\Input::post("new_password");

            // to be throttle
            // validate userid and password if empty or not
            if($val->run()){

                if(Auth::change_password($old_password, $new_password, $userid)){

                    // updates the first_login to 0, indicating that this user have undergo change password
                    $user = Model_Login::find("all", array("where" => array("username" => $userid), "limit" => 1));
                    $user = array_shift($user);

                    $user->first_login = 0;
                    $user->save();

                    // update to time
                    $this->update_time_pwd_change($userid);

                    $msg  = "<div class='alert alert-success'>";
                    $msg .= "<strong>New password saved.!</strong>";
                    $msg .= "</div>";

                    Session::set_flash("msg", $msg);
                    Session::set_flash("location", "login/redirectUser");
                    \Fuel\Core\Response::redirect("messages");

                }else{

                    // old_password is not correct
                    $msg[] = "Old password is incorrect.!";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("login/firstLogin", "refresh");

                }

            }else {

                // fields are empty or has errors
                // base on the validation errors by the
                // validation class
                $msg = $val->error_message();
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("login/firstLogin", "refresh");
            }

        }else {

            // csrf token is missing
            $msg[] = "Token missing or not valid.";
            Session::set_flash("msg", $msg);
            \Fuel\Core\Response::redirect("login/firstLogin", "refresh");
        }
    }

    /**
     * Mandatory change password process
     * happens every x number of days
     * depending on the settings stored
     * in the database
     *
     */
    public function post_changePassword() {
        $val = Validation::forge("change_password");
        $msg = null;

        // csrf check
        if(Security::check_token()){
            // csrf token is valid

            // set validation rules
            $val->add_field("old_password", "old password", "required");
            $val->add_field("new_password", "new password", "required|min_length[8]");
            $val->add_field("confirm_password", "confirm password", "required|min_length[8]|match_field[new_password]");

            $userid       = \Fuel\Core\Input::post("username");
            $old_password = \Fuel\Core\Input::post("old_password");
            $new_password = \Fuel\Core\Input::post("new_password");

            // to be throttle
            // validate userid and password if empty or not
            if($val->run()){

                if(Auth::change_password($old_password, $new_password, $userid)){

                    // update to time
                    $this->update_time_pwd_change($userid);

                    $msg  = "<div class='alert alert-success'>";
                    $msg .= "<strong>Password saved.!</strong>";
                    $msg .= "</div>";
                    Session::set_flash("msg", $msg);
                    Session::set_flash("location", "login/redirectUser");
                    \Fuel\Core\Response::redirect("messages");

                }else{

                    // old_password is not correct
                    $msg[] = "Old password is incorrect.!";
                    Session::set_flash("msg", $msg);
                    \Fuel\Core\Response::redirect("login/schedulePwdReset", "refresh");

                }

            }else {

                // fields are empty or has errors
                // base on the validation errors by the
                // validation class
                $msg = $val->error_message();
                Session::set_flash("msg", $msg);
                \Fuel\Core\Response::redirect("login/schedulePwdReset", "refresh");
            }

        }else {

            // csrf token is missing
            $msg[] = "Token missing or not valid.";
            Session::set_flash("msg", $msg);
            \Fuel\Core\Response::redirect("login/schedulePwdReset","refresh");
        }
    }

    /**
     * Check if userid exist in database
     *
     * @param $userid
     * @return bool
     */
    private function check_user($userid){
        $user = Model_Login::find("all", array("where" => array("username" => $userid), "limit" => 1));
        return count($user) > 0 ? true : false;
    }

    /**
     * Check if first time login
     *
     * @param $userid
     * @return bool
     */
    private function is_firstLogin($userid){
        $user = Model_Login::find("all", array("where" => array("username" => $userid), "limit" => 1));
        $user = array_shift($user);
        return ($user->first_login == 1) ? true : false;
    }

    /**
     * Checks if user's password has expired
     *
     * @param $userid
     * @return bool
     */
    private function is_pwd_expired($userid) {
        // user record
        $user = Model_Login::find("all", array("where" => array("username" => $userid), "limit" => 1));
        $user = array_shift($user);

        // settings record
        $settings = Model_Settings::find("all", array("where" => array("name" => "admin_settings")));
        $settings = array_shift($settings);

        // values are in seconds
        // time (in seconds) that the user last updated his password
        $user_last_update = $user->time_last_pwd_change;
        // the time (in seconds) that the user's password will expire
        // from the time the user last updated his/her password
        $pwd_reset_sched  = $settings->reset_pwd_after;

        // if current time is greater than the time the user's last update + expiration date
        // then user's password has expired
        return (time() > ($user_last_update + $pwd_reset_sched)) ? true : false;
    }

    /**
     * Updates the column "time_last_pwd_change"
     * in the database.
     *
     * @param $userid
     */
    private function update_time_pwd_change($userid){
        // user record
        $user = Model_Login::find("all", array("where" => array("username" => $userid), "limit" => 1));
        $user = array_shift($user);

        $user->time_last_pwd_change = time();
        $user->save();
    }

    /**
     * Record user's login attempts
     * Increase login_count by 1
     * Update user's last_login
     *
     * @param $userid
     */
    private function record_failed_login($userid){

        $user = Model_Login::find("all", array("where" => array("username" => $userid)));

        $user = array_shift($user);
        // udpate login_count
        $user->login_count++;
        // update last_login
        $user->last_login = time();
        //save changes
        $user->save();

    }

    /**
     * If user has successfully login
     * set login_count to 0
     * update last_login
     *
     * @param $userid
     */
    private function clear_failed_login($userid){

        $user = Model_Login::find("all", array("where" => array("username" => $userid)));

        $user = array_shift($user);
        // udpate login_count
        $user->login_count = 0;
        // update last_login
        $user->last_login = time();
        //save changes
        $user->save();
    }

    /**
     * Check user's login_count if it exceeds the limit
     * prevent user in loging in, return the time delay
     * before user can log in again
     * if it does not exceeds return 0
     *
     * @param $userid
     * @return float|int
     */
    private function throttle_failed_login($userid){
        //login attemp limit
        $limit = 2;
        // minutes of delay before login again
        $delay = 10;
        // delay in convert into seconds
        $actuall_delay = 60 * $delay;

        // fetch user records
        $user = Model_Login::find("all", array("select" => array("login_count", "last_login") ,"where" => array("username" => $userid)));

        // if user exist
        $user = array_shift($user);

        // check if user already exceed attempt limit
        if($user->login_count > $limit){
            // calculate time remaing
            $remaining_delay = ($user->last_login + $actuall_delay) - time();
            // convert the delay time to minutes
            $remain_time_in_mins = ceil($remaining_delay / 60);
            // return time remaining
            return $remain_time_in_mins;

        }else {
            return 0;
        }

    }
}
