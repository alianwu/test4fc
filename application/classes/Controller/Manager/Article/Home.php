<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Article_Home extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Article_Core');
    $this->model_category = Model::factory('Article_Core_Category');
    $this->model_faq = Model::factory('Article_Core_Faq');
    if ($this->auto_render == TRUE) { 
      $this->template->container = View::factory('manager/article/article');
    }
  }

  public function action_index()
  {
    $page = Arr::get($_GET, 'page', 1);
    $article = $this->model->get_list(NULL, $page);
    $category = $this->model_category->get_list_pretty();

    $view = View::factory('manager/article/article_index');
    $view->bind('article', $article);
    $view->bind('category', $category);

    $this->template->container->detail = $view;
  }
  

  public function action_add()
  { 
    $category = $this->model_category->get_list_pretty();
    $this->template->container->detail = View::factory('manager/article/article_add')
                                              ->set('category', $category);
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

  public function action_update()
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
      $this->result($ret?0:1);
    }
    else {
      $error = $post->errors('article');
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
    $view = View::factory('manager/article/article_faq');
    $view->bind_global('faq', $faq);
    $this->template->container->detail = $view;
  }

} 
