<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller_Template {

  public function before()
  {
    parent::before();
    $this->response->headers('cache-control', 'max-age=5');
  }
  public function action_index()
  {
    $this->template->content = View::factory('index');
  }

} // End Home
