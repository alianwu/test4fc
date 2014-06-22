<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Company extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Company');
  }

  public function action_index()
  {
    $view = View::factory('manager/company/company_index');
    $where = Arr::extract($_GET, array('keyword', 'display', 'page'));
    $list = $this->model->get_list($this->city_id, $where);
    $view->bind('list', $list);
    $this->view($view);
  }
  
  public function action_editor()
  {
    if ($this->request->method() == HTTP_Request::GET) {
      if($id = (int) Arr::get($_GET, 'id', 0)) {
        $data = $this->model->get($id, FALSE);
        if($data) {
          $_POST = $data;
          $_POST['image'] = json_decode($_POST['image'], TRUE); 
          $_POST['phone'] = json_decode($_POST['phone'], TRUE); 
          if ($_POST['phone']) {
            $pstr = '';
            foreach($_POST['phone'] as $v) {
              $pstr .= $v['c'].' '.$v['n']."\n"; 
            }
            $_POST['phone'] = $pstr; 
          }
        }
      }
    }
    else {
      if ($ih = Arr::get($_POST, 'image_history', FALSE)) {
        foreach ($ih as $k => $v) {
          $_POST['image'][] =  array(
            'src'=>$v, 
            'alt'=>Arr::path($_POST, 'image_desc.'.$k));
        }
      }
    }
    $view = View::factory('manager/company/company_editor');
    $this->view($view);
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
      'hot' => array(
            array('digit')),
      'city_area' => array(
            array('digit'),
            array('not_empty')),
      'city_area_shop' => array(
            array('digit'),
            array('not_empty')),
      'contact' => array(
            array('not_empty'),
            array('min_length', array(':value', 2)),
            array('max_length', array(':value', 10))),
      'email' => array(
            array('email')),
      'phone' => array(
            array('max_length', array(':value', 300))),
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
      'image_history' => array(
            array('not_empty'),
            array('is_array')),
      'image_desc' => array(
            array('not_empty'),
            array('is_array')),
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
      $view = View::factory('manager/company/company_editor_success')->set('ret', $ret);
      return $this->view($view);
    }
    else {
      $error = $post->errors('manager/company/company');
      $this->template->bind_global('error', $error);
    }
    $this->action_editor();
  }

  public function action_display()
  {
    $id = (int) Arr::get($_GET, 'id', 0);
    if ($id) {
      $ret = $this->model->update_hot($id, 'display');
      $this->result($ret);
    }
    $this->action_index();
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
