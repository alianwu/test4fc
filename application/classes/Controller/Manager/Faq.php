<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Faq extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model_faq = Model::factory('Faq');
    $this->model_faq_detail = Model::factory('Faq_Detail');
  }

  public function action_index()
  {
    $view = View::factory('manager/faq/faq_index');
    $where = Arr::extract($_GET, array('keyword', 'display', 'page', 'type', 'id'));
    if (isset($where['type']) && $where['type']) {
      $where['type'] = array_search($where['type'], $this->setting['module']);
    }
    $list = $this->model_faq->get_list($this->city_id, $where);
    $view->bind('list', $list);
    $this->view($view);
  }

  public function action_setting()
  {
    $this->action_404();
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
