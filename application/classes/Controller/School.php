<?php defined('SYSPATH') or die('No direct script access.');

class Controller_School extends Controller_Template {

  protected $model_school;

  public function before()
  {
    parent::before();
    $this->model_school = Model::factory('School');
  }

  public function action_index()
  {
    $type = Arr::get($_GET, 'type', 'index');
    $view =  View::factory('school/school');
    $view->set_global('type', $type);
    $this->view($view);
  }


  public function action_detail()
  {
    $id = (int) $this->request->param('id'); 
    if ($this->manager) {
      $article = $this->model_article->get_one($id, TRUE);
    }
    else {
      $article = $this->model_article->get_one_front($id);
    }
    if ($id 
      && $article) {
      $this->model_article->update_hot($id, 'hit');
      if ($article->type == 2) {
        $view = View::factory('article/article_live');
      }
      elseif ($article->type == 1) {
        $view = View::factory('article/article_picture');
        $article->image = json_decode($article->image);
      }
      else {
        $view = View::factory('article/article_detail');
      }
      if ($article->rel 
        && $article->rel_id) {
        switch($article->rel) {
          case 1:
            $data = Model::factory('House')->get_one($article->rel_id);
            $view->bind('rel', $data);
            $view->set('rel_name', 'house');
            break;
        } 
      }
      $view->bind_global('article', $article);
      $view->set_global('atype', 'category');
      $view->set_global('cid', $article->category);
      $this->view($view);
    }
    else {
      throw Kohana_HTTP_Exception_404();
    }
  }

} // End Home
