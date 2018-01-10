<?php

use Orm\Model as OrmModel;

class Model_Attendance extends OrmModel {

    protected static $_table_name = "attendance";

    protected static $_primary_key = array('attendance_id');

    protected static $_properties = array(
        "attendance_id",
        "userid",
        "timein",
        "timeout",
        "status"
    );
}