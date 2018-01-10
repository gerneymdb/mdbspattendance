<?php

use Orm\Model as OrmModel;

class Model_Leavesettings extends OrmModel {

    protected static $_table_name = "leave_settings";

    protected static $_primary_key = array('leave_settings_id');

    protected static $_properties = array(
        "leave_settings_id",
        "leave_name",
        "days_alloted"
    );
}