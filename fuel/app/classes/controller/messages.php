<?php

class Controller_Messages extends \Fuel\Core\Controller_Template {

    public $template = 'messages_template';

    public function after($response){
        parent::after($response);
        $response = parent::after($response);
        $response = $response->set_header('Cache-Control', 'no-store, no-cache, must-revalidate,  max-age=0');
        $response = $response->set_header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $response = $response->set_header('Pragma', 'no-cache');
        return $response;
    }

    public function action_index() {
       $this->template->title = "Unauthorize";
       $this->template->content = \Fuel\Core\View::forge("message/index");
    }
}