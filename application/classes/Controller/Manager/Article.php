<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Article extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Article_Core');
    $this->model_category = Model::factory('Article_Core_Category');
    $this->model_faq = Model::factory('Article_Core_Faq');
  }

  public function action_index()
  {
    $page = Arr::get($_GET, 'page', 1);
    $article = $this->model->get_list(NULL, $page);
    $category = $this->model_category->get_list_pretty();

    $view = View::factory('manager/article/article_index');
    $view->bind('list', $article);
    $view->bind('category', $category);

    $this->view($view);
  }
  

  public function action_editor()
  { 
    $id = (int) Arr::get($_GET, 'id', 0);
    if ($id) {
      $data = $this->model->get_one($id);
      if($data) {
        $_POST = $data;
      }
    }
    $category = $this->model_category->get_list_pretty();
    $view = View::factory('manager/article/article_editor')
                ->set('category', $category);
    $this->view($view);
  }
  
  public function action_save()
  {
    $fields = array(
      'aid' => array(
            array('not_empty'),
            array('digit'),
          ),
      'city_id' => array(
            array('digit'),
            array('not_empty'),
          ),
      'subject' => array(
            array('trim'),
            array('not_empty'),
            array('max_length', array(':value', 100)),
          ),
      'body' => array(
            array('max_length', array(':value', 5000)),
            array('not_empty'),
          ),
      'hot' => array(
            array('digit'),
          ),
      'category' => array(
            array('digit'),
            array('not_empty'),
          ),
      'rel' => array(
            array('digit'),
          ),
      'rel_id' => array(
            array('digit'),
          ),
      'source' => array(
            array('max_length', array(':value', 10)),
          ),
      'tag' => array(
            array('max_length', array(':value', 1000)),
          ),
      'relation' => array(
            array('max_length', array(':value', 1000)),
          ),
      'weight' => array(
            array('digit'),
            array('not_empty'),
          ),
      'display' => array(
            array('digit'),
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
      if ($ret) {
        $view = View::factory('manager/article/action_editor_success')
                  ->set('ret', $ret);
        return $this->view($view);
      }
      else {
        $this->result(1);
      }
    }
    else {
      $error = $post->errors('manager/article');
      $this->result(1);
      $this->template->bind_global('error', $error);
    }
    $this->action_editor();
  }

  public function action_api()
  {
    $check = parent::action_api();
    if ($check) {
      //
    }
    $this->action_index();
  }

  public function action_live()
  {
    return $this->action_404();
  }

} 
