<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Session extends Controller {

  public function action_redis()
  {
    Session::$default = 'redis';
    Session::instance()->set('name', 'value');
    echo Session::instance()->get('name');
  }
  
}