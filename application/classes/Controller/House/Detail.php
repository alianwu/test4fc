<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House_Detail extends Controller_Template {

  public $manager; 
  public function before()
  {
    parent::before();
    $this->manager = $this->get_user('manager.user');
    $this->model = Model::factory('House_New');
  }

  public function action_display()
  {
    $hid = (int) $this->request->param('id');
    if ($this->manager) {
      $data = $this->model->get_one($hid);
    }
    else {
      $data = $this->model->get_one_front($hid);
    }
    if ($data == NULL) {
      throw new Kohana_HTTP_Exception_404();
    }
    $this->model->update_hit($hid);
    $view =  View::factory('house/detail');
    $view->bind_global('house', $data);
    $this->template->container = $view;
  }

} // End House
