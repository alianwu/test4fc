<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller_Template {
  
  protected $model_article,
            $model_category,
            $model_house,
            
            $type = array('house_new', 'article');
  public function before()
  {
    parent::before();
    $this->model_article = Model::factory('Article');
    $this->model_category = Model::factory('Article_Category');
    $this->model_house = Model::factory('House_New');
    // $this->response->headers('cache-control', 'max-age=5');
  }
  public function action_index()
  {
    $type = Arr::get($_GET, 'type', 'house_new');
    if (in_array($type, $this->type) == FALSE) {
        $type = 'house_new';
    }

    $underground = $this->model_city->get_city_pretty($this->city_id, 2);
    $category = $this->model_category->get_list_pretty();
    $view =  View::factory('search');
    $view->bind_global('city_underground', $underground);
    $view->bind_global('article_category', $category);
    $view->bind_global('initialize_type', $type);

    $this->template->container = $view;
  }

} // End Home
