<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Favorite extends Controller_Template {
  
  public $template = 'template';

  protected $model_house;

  public function before()
  {
    parent::before();
    if ($this->user === NULL) {
      $this->redirect('user/qq_index');
    }
    // $this->response->headers('cache-control', 'max-age=5');
  }
  public function action_index()
  {
    $view =  View::factory('user/favorite');
    $this->view($view);
  }

} // End Home
