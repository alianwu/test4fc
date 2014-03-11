<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_User extends Controller_Manager_Template {
  
  public function before()
  {
    parent::before();
    $this->model = Model::factory('User'); 
  }

  public function action_index()
  {
    $user = $this->model->get_list();
    $view = View::factory('manager/user/index');
    $view->bind_global('users', $user);
    $this->template->container = $view;
  }

  public function action_actived()
  {
    $id = (int) Arr::get($_GET, 'id');
    if ($id) {
      $this->model->actived($id);
    }
    $this->action_index();
  }

  public function action_delete()
  {
    $id = (int) Arr::get($_GET, 'id');
    if ($id) {
      $this->model->delete($id);
    }
    $this->action_index();
  }
} 
