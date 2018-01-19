<?php

use Orm\Model as OrmModel;

class Model_Employee extends OrmModel {

    protected static $_table_name = "employee";

    protected static $_primary_key = array('employee_id');

    protected static $_properties = array(
        "employee_id",
        "userid",
        "fname",
        "mname",
        "lname",
        "shift_id",
        "co_position",
        "birthdate",
        "civil_status"
    );

    public static function is_day_off(){
        $user_shift = null;

        $shift_id = Model_Employee::get_shift_id();
        // get the shift information the employee has
        $result = Model_Workschedule::find("all", array(
            "where" => array(
                array(
                    "shift_id", "=", $shift_id
                )
            )
        ));
        $shift_id_object = $result;
        if(count($result) > 0){
            $user_shift = array_shift($result);
        }else {
            die("No shift available in the database");
        }

        // get the work days and day off of the employee
        $work_days = explode(",", $user_shift->work_days);
        $day_off   = explode(",", $user_shift->day_off);

        // check if today is day_off
        $day = strtoupper(strftime("%a", time()));

        return in_array($day, $day_off) ? $shift_id_object : false;
    }

    private static function get_shift_id(){
        $shift_id = null;

        // get username(userid) of currently logged in user
        $userid = \Fuel\Core\Session::get("username");

        // get employee shift id
        $result = Model_Employee::find("all", array(
            "where" => array(
                array("userid", "=", $userid),
            )
        ));

        if(count($result) > 0){
            $emp_info = array_shift($result);
            $shift_id = $emp_info->shift_id;
        }

        return $shift_id;
    }
}