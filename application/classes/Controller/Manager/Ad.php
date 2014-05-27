<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Ad extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    //$this->model = Model::factory('Ad');
  }

  public function action_index()
  {
    $this->action_404();
    //$view = View::factory('manager/faq/faq_index');
    //$where = Arr::extract($_GET, array('keyword', 'display', 'page'));
    //$list = $this->model->get_list($this->city_id, $where);
    //$view->bind('list', $list);
    //$this->view($view);
  }
  
  public function action_api()
  {
    $check = parent::action_api();
    if ($check) {
      //
    }
    $this->action_index();
  }

} 
