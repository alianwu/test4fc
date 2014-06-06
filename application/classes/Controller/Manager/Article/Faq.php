<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Article_Faq extends Controller_Manager_Template {

  public $limit = 30;

  public function before()
  {
    parent::before();
    $this->model_faq = Model::factory('Article_Faq');
  }

  public function action_index()
  {
    $start = max((int) Arr::get($_GET, 'start', 0), 0);
    $where = array('city_id'=>$this->city_id);
    $list = $this->model_faq->get_more($where, $start, $this->limit);
    $view = View::factory('manager/article/article_faq');
    $view->bind('list', $list);
    $this->view($view);
  }

  public function action_detail()
  {
    $aid = (int) Arr::get($_GET, 'id', 0);
    if ($aid) {
      $list = $this->model_faq->get_detail($aid);
      $article = Model::factory('Article')->get($aid);
      $view = View::factory('manager/article/article_faq_detail');
      $view->bind('list', $list);
      $view->bind('article', $article);
      $this->view($view);
    }
  }

} 
