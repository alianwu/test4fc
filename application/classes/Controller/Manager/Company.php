<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Company extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Company');
    if ($this->auto_render == TRUE) { 
      $this->template->container = View::factory('manager/company');
    }
  }

  public function action_index()
  {
    $page = Arr::get($_GET, 'page', 1);
    $company = $this->model->get_list(NULL, $page);

    $view = View::factory('manager/company_index');
    $view->bind('company', $company);

    $this->template->container->detail = $view;
  }
  

  public function action_add()
  { 
    $this->template->container->detail = View::factory('manager/company_add');
  }
  
  public function action_editor()
  {
    $aid = Arr::get($_GET, 'aid', 0);
    $data = $this->model->get_one($aid);
    if($data === FALSE) {
      throw new Kohana_HTTP_Exception_404();
    }

    $_POST = $data;
    $this->action_add();
  }

  public function action_save()
  {
    $fields = array(
      'cid' => array(
            array('not_empty'),
            array('digit')),
      'city_id' => array(
            array('digit'),
            array('not_empty')),
      'name' => array(
            array('not_empty'),
            array('min_length', array(':value', 3)),
            array('max_length', array(':value', 100))),
      'contact' => array(
            array('not_empty'),
            array('min_length', array(':value', 2)),
            array('max_length', array(':value', 10))),
      'email' => array(
            array('email')),
      'phone' => array(
            array('max_length', array(':value', 15))),
      'address' => array(
            array('not_empty'),
            array('max_length', array(':value', 100))),
      'description' => array(
            array('max_length', array(':value', 1000))),
      'information' => array(
            array('max_length', array(':value', 1000))),
      'service' => array(
            array('max_length', array(':value', 1000))),
      'display' => array(
            array('digit')),
      'lat' => array(
            array('is_numeric')),
      'lng' => array(
            array('is_numeric')),
      'image' => array(
            array('Upload::check')),
      'csrf' => array(
            array('not_empty'),
            array('Security::check')),
    );
    $post = Validation::factory( Arr::extract($_FILES + $_POST,  array_keys($fields)) );
    foreach ($fields as $k => $v) {
      $post->rules($k, $v);
    }
    if($post->check()) {
      $data = $post->data();
      $ret = $this->model->save_one($data);
      $this->result($ret);
    }
    else {
      $error = $post->errors('company');
      // print_r($error);
      $this->result(1);
      $this->template->bind_global('error', $error);
    }
    $this->action_add();
  }

  public function action_display()
  {
    $aid = Arr::get($_GET, 'aid', 0);
    $ret = $this->model->display_one($aid);
    $this->result($ret);
    $this->action_index();
  }

  public function action_delete()
  {
    $aid = Arr::get($_GET, 'aid', 0);
    $ret = $this->model->delete_one($aid);
    $this->result($ret);
    $this->action_index();
  }

  public function action_faq()
  {
    $aid = Arr::get($_GET, 'aid', 0);
    $page = Arr::get($_GET, 'page', 1);
    $faq = $this->model_faq->get_list($aid, $page);
    $view = View::factory('manager/company/company_faq');
    $view->bind_global('faq', $faq);
    $this->template->container->detail = $view;
  }

} 
