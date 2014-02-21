<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Article_Category extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Article_Core_Category');
    if ($this->auto_render == TRUE) { 
      $this->template->container = View::factory('manager/article/article');
    }
  }

  public function action_index()
  {
    $category = $this->model->get_list();

    $view = View::factory('manager/article/article_category');
    $view->bind('category', $category);

    $this->template->container->detail = $view;
  }

  public function action_update()
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
      $data = $post->as_array();
      $ret = $this->model->save_one($data);
      if ($ret) {
        $this->message['error'] = 0;
      }
      else { }
      $this->template->set_global('message', $this->message);
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

  public function action_delete()
  {
    $acid = (int) Arr::get($_GET, 'acid', 0);
    $ret = $this->model->delete_one($acid);
    if ($ret) {
      $this->message['error'] = 0; 
    }
    $this->template->bind_global('message', $this->message);
    $this->action_index();
  }

} 
