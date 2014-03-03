<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House_Search extends Controller_Template {
  
  public function before()
  {
    parent::before();
  }

  public function action_index()
  {
    $view =  View::factory('search/reslut');
    $view->set('type', 'house_new');
    $this->template->container = $view;
  }

} // End Home
