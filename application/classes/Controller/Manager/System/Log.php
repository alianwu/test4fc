<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_Log extends Controller_Manager_Template {
  
  public $limit = 30;

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Log'); 
  }

  public function action_index()
  {
    $start = max((int) Arr::get($_GET, 'start', 0), 0);
    $list = $this->model->get_more(NULL, $start, $this->limit);
    $view = View::factory('manager/system/log');
    $view->bind_global('list', $list);
    $view->set('start', $start);
    $view->set('limit', $this->limit);
    $this->view($view);
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
