<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House_Home extends Controller_Template {
  
  protected $view_home;
  
  // 附近房源
  protected $radius = 1500000000000;
  protected $model_article, 
            $model_category; 

  public function before()
  {
    parent::before();
    // $this->response->headers('cache-control', 'max-age=5');
    if ($this->auto_render) {
      $city_group = $this->model_city->get_city_group($this->city_id);

      $this->view_home = View::factory('house/home');
      $this->view_home->bind_global('city_group', $city_group);
      $this->template->container = $this->view_home;
    }
  }

  private function _action_list($view_name, $data)
  {
    $view =  View::factory($view_name);
    $view->set_global($data);
    
    if ($this->auto_render) {
      $this->template->container->container = $view;
    }
    else {
      $this->response->body($view);
    }

  }

  public function action_index()
  {
    $this->action_near();
  }

  public function action_near()
  {
    $page = max((int) Arr::get($_GET, 'page', 1), 1);
    $lat  = (int) Arr::get($_GET, 'lat', 0);
    if ($lat == 0) {
      $lat = $this->city_lat;
    }
    $lng  = (int) Arr::get($_GET, 'lng', 0);
    if ($lng == 0) {
      $lng = $this->city_lng;
    }
    $city_id = (int) Arr::get($_GET, 'city_id', 0);
    if ($city_id == 0) {
      $city_id = $this->city_id;
    }
    $radius  = (int) Arr::get($_GET, 'radius', $this->radius);

    $this->model = Model::factory('House_New');
    $house = $this->model->get_near_front($city_id, $lat, $lng, $radius, $page);

    $data = array(
      'house' => $house,
      'house_type' => 'near',
      'city_pretty' => $this->city_pretty,
      'city_id' => $this->city_id);

    $this->_action_list('house/list_new', $data);
  }

  public function action_hot()
  {
    $page = max((int) Arr::get($_GET, 'page', 1), 1);
    $this->model = Model::factory('House_New');
    $house = $this->model->get_hot_front($this->city_id, $page);
    $data = array(
      'house' => $house,
      'house_type' => 'hot',
      'city_pretty' => $this->city_pretty,
      'city_id' => $this->city_id);

    $this->_action_list('house/list_new', $data);
  }

  public function action_article()
  {
    $page = max((int) Arr::get($_GET, 'page', 1), 1);
    $this->model_article = Model::factory('Article');
    $article = $this->model_article->get_list_category_front($this->city_id, 1, $page);
    
    $this->model_category = Model::factory('Article_Category');
    $category = $this->model_category->get_list_pretty();

    $data = array(
      'article' => $article,
      'category' => $category
      );
    $this->_action_list('house/list_article', $data);
  }

} // End Home
