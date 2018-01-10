<?php

use Orm\Model as OrmModel;

use Fuel\Core\DB as DB;

class Model_Holidays extends OrmModel {

    protected static $_table_name = "holidays";

    protected static $_primary_key = array('holiday_id');

    protected static $_properties = array(
        "holiday_id",
        "holiday_name",
        "start_day",
        "end_day",
        "description",
        "type",
        "with_work"
    );

    public static function is_regular_holiday(){

        // the date today
        // to be check against the holiday db
        // if it is a holiday
        $today = strftime("%Y-%m-%d", time());

        // get last number of the string
        $str = explode("-", $today);

        $last_no = $str[count($str) - 1];
        $last_no++;

        $end_of_the_day = $str[0] . "-" . $str[1] . "-" . $last_no;

        // find out if todays date is regular holiday
        $result = self::find('all', array(
                            "where" => array(
                                array('start_day', '>=', $today),
                                array('end_day', "<", $end_of_the_day),
                                array('type', '=', 'Regular Holiday')
                            )
                        )
                    );

        // return result
        return count($result) > 0 ? $result : false;
    }

    public static function is_special_holiday(){
        // the date today
        // to be check against the holiday db
        // if it is a holiday
        $today = strftime("%Y-%m-%d", time());

        // get last number of the string
        $str = explode("-", $today);

        $last_no = $str[count($str) - 1];
        $last_no++;

        $end_of_the_day = $str[0] . "-" . $str[1] . "-" . $last_no;

        // find out if todays date is special holiday
        $result = self::find('all', array(
                "where" => array(
                    array('start_day', '>=', $today),
                    array('end_day', "<", $end_of_the_day),
                    array('type', '=', 'Special Holiday')
                )
            )
        );

        // return result
        return count($result) > 0 ? $result : false;
    }
}