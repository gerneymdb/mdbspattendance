<?php

use Orm\Model as OrmModel;

class Model_Leave extends OrmModel {

    protected static $_table_name = "leave";

    protected static $_primary_key = array('leave_id');

    protected static $_properties = array(
        "leave_id",
        "userid",
        "start_leave",
        "end_leave",
        "type",
        "reason",
        "status",
        "approved_by",
        "attachments",
        "date_filed",
        "comments"
    );

    /**
     * Checks if currently logged in user
     * has an approved leave record in the
     * database that dates today
     *
     * @return bool|OrmModel|OrmModel[]
     */
    public static function is_leave(){
        $userid = \Fuel\Core\Session::get("username");
        // the date today
        // to be check against the leave db
        // if user is on leave
        $today = strftime("%Y-%m-%d", time());

        // get last number of the string
        $str = explode("-", $today);

        $last_no = $str[count($str) - 1];
        $last_no++;

        $end_of_the_day = $str[0] . "-" . $str[1] . "-" . $last_no;

        // find out if user is still on leave
        $result = self::find('all', array(
                "where" => array(
                    array("userid", "=", $userid),
                    array('start_leave', '>=', $today." 00:00:00"),
                    array('end_leave', "<", $end_of_the_day),
                    array('status', '=', 'approved')
                )
            )
        );

        // return result
        return count($result) > 0 ? $result : false;
    }
}