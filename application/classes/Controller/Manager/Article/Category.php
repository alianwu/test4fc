<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Article_Category extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Article_Core_Category');
  }

  public function action_index()
  {
    $list = $this->model->get_list();
    $view = View::factory('manager/article/article_category');
    $view->bind('list', $list);
    $this->view($view);
  }

  public function action_save()
  {
    $fields = array(
      'acid' => array(
            array('not_empty'),
            array('digit'),
          ),
      'city_id' => array(
            array('digit'),
            array('not_empty'),
          ),
      'name' => array(
            array('not_empty'),
            array('max_length', array(':value', 30)),
          ),
      'weight' => array(
            array('digit'),
            array('not_empty'),
          ),
      'csrf' => array(
            array('not_empty'),
            array('Security::check'),
          ),
    );
    $post = Validation::factory( Arr::extract($_POST,  array_keys($fields)) );
    foreach ($fields as $k => $v) {
      $post->rules($k, $v);
    }
    if( $post->check() ) {
      $data = $post->data();
      $ret = $this->model->save_one($data);
      $this->result($ret);
    }
    else {
      $error = $post->errors('article/category');
      $this->template->bind_global('error', $error);
    }
    $this->action_index();
  }
  

  public function action_editor()
  {
    $acid = (int) Arr::get($_GET, 'acid', 0);
    if ($acid) {
      $one = $this->model->get_one($acid);
      if ($one) {
        $_POST = $one;
      }
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
