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
    $this->model_category = Model::factory('Article_Category');
    $this->category = $this->model_category->get_list_pretty();
    $this->template->bind_global('category', $this->category); 
  }

  public function action_index()
  {
    $atype = Arr::get($_GET, 'atype', 'index');
    $view =  View::factory('article/article');
    switch($atype) {
      case 'category':
        $cid = (int) Arr::get($_GET, 'cid');
        if (isset($this->category[$cid])) {
          $view->set_global('cid', $cid);
        }
        else {
          throw Kohana_HTTP_Exception_404();
        }
        break;
      case 'tag':
        $tid = (int) Arr::get($_GET, 'tid');
        $tag = $this->model_tag->get($tid);
        if ($tag) {
          $view->set_global('tag', $tag);
        }
        else {
          throw Kohana_HTTP_Exception_404();
        }
        break;
    }
    $view->set_global('atype', $atype);
    $this->view($view);
  }


  public function action_detail()
  {
    $id = (int) $this->request->param('id'); 
    if ($id 
      && $article = $this->model_article->get_one_front($id)) {
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
