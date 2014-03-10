<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Home extends Controller_Manager_Template {
  
  public function action_index()
  {
    $this->template->container = View::factory('manager/index');
  }

} 
