<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Article extends Controller_Template {

  protected $model_category,
    $model_tag,
    $category;

  public function before()
  {
    parent::before();

    $this->model_article = Model::factory('Article');
    $this->model_tag = Model::factory('Article_Tag');
    $this->category = Cache::instance()->get('article_category', FALSE);
    if ($this->category == FALSE) {
      $this->category = Model::factory('Article_Category')->get_list_pretty();
      Cache::instance()->set('article_category', $this->category);
    }
    if ($this->auto_render) {
      $this->template->bind_global('category', $this->category); 
    }
  }

  private function _action_list($data)
  {
    $view =  View::factory('article/list');
    $view->set_global($data);
    if ($this->auto_render) {
      $home =  View::factory('article/home');
      $home->container = $view;
      $this->template->container = $home;
    }
    else {
      $this->response->body($view);
    }

  }

  public function action_index()
  {
    $article = $this->model->get_list_front($this->city_id, $page=1);
    $this->_action_list(array('article'=>$article,'category_id'=>1, 'type'=>'index')); 
  }

  public function action_category()
  { 
    $id = (int) $this->request->param('id'); 
    $article = $this->model->get_list_category_front($this->city_id, $id);
    $this->_action_list(array('article'=>$article, 'category_id'=>$id, 'type'=>'cat')); 
  }

  public function action_tag()
  {
    $id = (int) $this->request->param('id'); 
    $article = $this->model->get_list_tag_front($this->city_id, $id);
    $tag = $this->model_tag->get_one($id);
    $this->_action_list(array(
      'article'=>$article, 
      'tag'=>$tag, 
      'tag_id'=>$id, 
      'type'=>'tag')); 
  }

  public function action_detail()
  {
    $id = (int) $this->request->param('id'); 
    if ($id 
      && $article = $this->model_article->get_one_front($id)) {
      $this->model_article->update_hot($id, 'hit');
      $view = View::factory('article/detail');
      $view->bind_global('article', $article);
      $this->view($view);
    }
    else {
      throw Kohana_HTTP_Exception_404();
    }
  }

} // End Home
