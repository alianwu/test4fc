<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller_Template {
  
  public $template = 'template-home';

  public function before()
  {
    parent::before();
    $this->model = Model::factory('House_New');
    // $this->response->headers('cache-control', 'max-age=5');
  }
  public function action_index()
  {
    $house = $this->model->get_list_front($this->city_id, 1);
    $city_group = $this->model_city->get_city_group($this->city_id);
    $view =  View::factory('index');
    $view->bind_global('house', $house);
    $view->bind_global('city_group', $city_group);
    $this->template->container = $view;
  }

} // End Home
