<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test_Home extends Controller_Test_Template {

  public function before()
  {
    parent::before();
    $this->response->headers('cache-control', 'max-age=5');
  }
  public function action_index()
  {
    echo $test = json_encode(array('abc','中文'));
    print_r(json_decode($test));
    $this->template->content = View::factory('test/index');
  }

} // End Home
