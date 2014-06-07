<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House_map extends Controller_Template {

  public function before()
  {
    parent::before();
  }

  public function action_index()
  {
    $view = View::factory('house/house_map');
    $view->bind_global('city_lat', $this->city_lat);
    $view->bind_global('city_lng', $this->city_lng);
    $view->bind_global('core', $this->core);
    $this->view($view);
  }

}
