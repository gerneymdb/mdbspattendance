<?php

use Orm\Model as OrmModel;

class Model_Workschedule extends OrmModel {

    protected static $_table_name = "work_schedule";

    protected static $_primary_key = array('shift_id');

    protected static $_properties = array(
        "shift_id",
        "shift_name",
        "work_days",
        "day_off",
        "start_shift",
        "end_shift"
    );
}