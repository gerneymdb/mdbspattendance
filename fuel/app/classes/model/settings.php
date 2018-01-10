<?php

use Orm\Model as OrmModel;

class Model_Settings extends OrmModel {
    protected static $_table_name = "settings";

    protected static $_properties = array(
        "id",
        "default_pwd",
        "reset_pwd_after",
        "session_timeout_after"
    );
}