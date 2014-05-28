<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Faq extends Controller_Template {
  
  protected $type;
  protected $id;
  protected $sort;
  protected $data = NULL;

  public function before()
  {
    parent::before();
    $this->id = (int) Arr::get($_GET, 'id', 0);
    $this->type = Arr::get($_GET, 'type', FALSE);
    $this->sort = Arr::get($_GET, 'sort', 'latest');
    $this->model_faq = Model::factory('Faq');
    $this->model_faq_detail = Model::factory('Faq_Detail');
  }

  public function action_index()
  {
    switch($this->type) {
      case 'house':
        $this->model = Model::factory('House');
        break;
      default:
        throw new Kohana_HTTP_Exception_404();
    }
    if ($this->id
        && $data = $this->model->get_one_front($this->id)) {
      $view =  View::factory('faq/faq');
      $view->bind('data', $data);
      $view->set('id', $this->id);
      $view->set('sort', $this->sort);
      $view->set('type', $this->type);
      $this->view($view);
    }
    else {
      throw new Kohana_HTTP_Exception_404();
    }
  }
  public function action_list()
  {
    $this->action_index();
  }

  public function action_detail()
  {
    $fid = (int) $this->request->param('id');
    $where = array('fid'=>$fid);
    if ($fid 
      && $faq = $this->model_faq->get_one_front($this->city_id, $where)) {
      $view =  View::factory('faq/faq_detail');
      $view->bind_global('fid', $fid);
      $view->bind_global('faq', $faq);
      $this->view($view);
    }
    else {
      throw new Kohana_HTTP_Exception_404();
    }
  }

} // End House
