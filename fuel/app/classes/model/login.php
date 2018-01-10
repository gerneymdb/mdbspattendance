<?php

use Orm\Model as OrmModel;

class Model_Login extends OrmModel {


    protected static $_table_name = "users";

    protected static $_properties = array(
        "id",
        "username",
        "password",
        "group",
        "email",
        "last_login",
        "login_hash",
        "profile_fields",
        "created_at",
        "updated_at",
        "login_count",
        "first_login",
        "time_last_pwd_change"
    );

    /**
     * Logs out the currently logged in user
     */
    public static function logout() {
        // delete login session time
        \Fuel\Core\Session::delete("login_session_time");
        // log out session
        \Auth\Auth::logout();
    }

    /**
     * Make sure that only authenticated, valid session,
     * and not banned user is able to access the system
     *
     * @return bool
     */
    public static function is_user_logged_session_valid() {
        // get settings information
        $settings = Model_Settings::find("all", array("where" => array("name" => "admin_settings")));
        $settings = array_shift($settings);

        // the limit of the session in seconds
        // its value come from the database (table name 'settings')
        $session_timeout_after = $settings->session_timeout_after;
        // the time the user logs in
        $login_session_time    = \Fuel\Core\Session::get("login_session_time");


        // checks if there are username and login_hash variables in sessions
        // and user's login_has == to sessoin login_hash
        if(!\Auth\Auth::check()){
            // no user logged in
            self::logout();
            $msg[] = "Your not logged in or your logged in session has already expired.";
            \Fuel\Core\Session::set_flash("msg", $msg);
            return false;
        }

        // checks if user is banned
        $user_group = \Auth\Auth::get_groups();
        if($user_group[0][1] == -1){
            // user is banned
            $msg[] = "You are currently banned from the system";
            \Fuel\Core\Session::set_flash("msg", $msg);

            // if banned user is currently logged in, logged him out
            if(\Auth\Auth::check()){
                Model_Login::logout();
            }
            return false;
        }

        // if current time is greater than the logged_session_time + the session_timeout_after
        // means the user's session has expired, logout user.
        if(time() > ($login_session_time + $session_timeout_after)){
            self::logout();
            $msg[] = "Your logged in session has already expired";
            \Fuel\Core\Session::set_flash("msg", $msg);
            return false;
        }else {
            // if not idle, update login_session_time.
            \Fuel\Core\Session::set("login_session_time", time());
        }

        return true;
    }

}