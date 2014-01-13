<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test_Session extends Controller {

  public function action_redis()
  {
    Session::$default = 'redis';
    Session::instance()->set('name', 'value');
    echo 'name: ' . Session::instance()->get('name');
  }
  
}