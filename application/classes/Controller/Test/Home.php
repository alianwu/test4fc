<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test_Home extends Controller_Test_Template {

  public function before()
  {
    parent::before();
    $this->response->headers('cache-control', 'max-age=5');
  }
  public function action_index()
  {
    $auth = 
    $this->template->content = View::factory('test/index');
  }

} // End Home
