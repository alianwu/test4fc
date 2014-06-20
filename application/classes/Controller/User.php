<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Template {
  
  public $auto_render = FALSE;

  public function action_logout()
  {
    Session::instance()->destroy($this->us_name);
    $this->redirect('/');
  }
} // End User
