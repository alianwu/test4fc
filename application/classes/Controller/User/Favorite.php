<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Favorite extends Controller_Template {
  
  public $template = 'template-home';

  protected $model_house;

  public function before()
  {
    parent::before();
    $this->model_house = Model::factory('House_New');
    // $this->response->headers('cache-control', 'max-age=5');
  }
  public function action_index()
  {
    $view =  View::factory('user/favorite');
    $this->template->container = $view;
  }

} // End Home
