<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_House_Faq extends Controller_Manager_Template {


  public function before()
  {
    parent::before();
    $this->model = Model::factory('House_Faq');
    $this->model_detail = Model::factory('House_Faq_Detail');
    if ($this->auto_render == TRUE) { 
      $this->template->container = View::factory('manager/house/house');
    }
  }

  public function action_index()
  {
    $page = Arr::get($_GET, 'page', 1);
    $faq = $this->model->get_list($this->city_id, $page);

    $view = View::factory('manager/house/house_faq');
    $view->bind('faq', $faq);

    $this->template->container->detail = $view;
  }
  
  public function action_detail()
  {
    $page = (int) Arr::get($_GET, 'page', 1);
    $fid  = (int) Arr::get($_GET, 'fid', 0);
    $faq = $this->model_detail->get_list($fid, $page);

    $view = View::factory('manager/house/house_faq');
    $view->bind('faq', $faq);

    $this->template->container->detail = $view;
  }

  public function action_display()
  {
    $fid = (int) Arr::get($_GET, 'fid', 1);
    $data = $this->model->display($fid);
    $this->action_index();
  }

  public function action_delete()
  {
    $fid = (int) Arr::get($_GET, 'fid', 1);
    $data = $this->model->delete($fid);
    $this->action_index();
  }

  public function action_display_detail()
  {
    $fid = (int) Arr::get($_GET, 'fid', 1);
    $data = $this->model_detail->display($fid);
    $this->action_detail();
  }

  public function action_delete_detail()
  {
    $fid = (int) Arr::get($_GET, 'fid', 1);
    $data = $this->model_detail->delete($fid);
    $this->action_detail();
  }

} 
