<?php
// form input validation
use \Fuel\Core\Validation as Validation;
// csrf
use \Fuel\Core\Security as Security;
// session class
use Fuel\Core\Session as Session;
// authentication
use Auth\Auth as Auth;

class Controller_Admin extends \Fuel\Core\Controller_Template {

    public $template = 'admin_template';

    public function action_index() {
        $this->template->title = "Attendance Management";
        $this->template->content = \Fuel\Core\View::forge("admin/index");
    }

}