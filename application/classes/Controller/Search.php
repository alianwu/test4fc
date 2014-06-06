<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller_Template {
  
  protected $model_article,
            $model_category,
            $model_setting,
            $model_house,
            
            $stype = array('house_new', 'article');
  public function before()
  {
    parent::before();
    $this->model_article = Model::factory('Article');
    $this->model_category = Model::factory('Article_Category');
    $this->model_house = Model::factory('House');
    // $this->response->headers('cache-control', 'max-age=5');
  }

  public function action_index()
  {
    $stype = Arr::get($_GET, 'stype', 'house_new');
    if (in_array($stype, $this->stype) == FALSE) {
        $stype = 'house_new';
    }

    $underground = $this->model_city->get_city_pretty($this->city_id, 2);
    $category = $this->model_category->get_list_pretty();

    $city_group = $this->model_city->get_city_group($this->city_id);
    
    $view =  View::factory('search/search');
    $view->set('stype', $stype);
    $view->bind_global('city_underground', $underground);
    $view->bind_global('article_category', $category);
    $view->bind_global('initialize_type', $type);
    $view->bind_global('hotsearch', $hotsearch);
    $view->bind_global('city_group', $city_group);
    $this->view($view);
  }

  public function action_result()
  {
    $stype = Arr::get($_GET, 'stype', 'house_new');
    if (in_array($stype, $this->stype) == FALSE) {
        $stype = 'house_new';
    }

    $view =  View::factory('search/search_result');
    $view->set('stype', $stype);
    $this->view($view);
  }


  public function action_map()
  {
    $view =  View::factory('search/map');
    $this->view($view);
  }

} // End Home
