<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Company extends Controller_Api {

  public function before()
  {
    parent::before();
    $this->model_company = Model::factory('Company');
  }

  public function action_search()
  {
    $data = Arr::extract($_GET, array('keyword', 'area', 'price', 'shop', 'page'));
    $data = $this->model_company->get_list_front($this->city_id, $data);
    if ($data) {
        $this->result(0, $data->as_array());
    }
  }

  public function action_near()
  {
    $page = (int) Arr::get($_GET, 'page', 1);
    $page = max($page, 1);
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
    $radius  = (int) Arr::get($_GET, 'radius', $this->city_radius);
    $geo = $this->session->get('geo');
    if ($geo) {
      $data = $this->model_company->get_near_front($city_id, $lat, $lng, $radius, $page);
    }
    else {
      $where = array('page'=>$page);
      $data = $this->model_company->get_list_front($this->city_id, $where);
    }
    if ($data) {
      $this->result(0, $data->as_array());
    }
    else {
      $this->result(1);
    }
  }

  public function action_hot()
  {
    $page = max((int) Arr::get($_GET, 'page', 1), 1);
    $data = $this->model_company->get_hot_front($this->city_id, $page);
    if ($data) {
      $this->result(0, $data->as_array());
    }
    else {
      $this->result(1);
    }
  }
  
  public function action_latest()
  {
    $page = max((int) Arr::get($_GET, 'page', 1), 1);
    $data = $this->model_company->get_latest_front($this->city_id, $page);
    if ($data) {
      $this->result(0, $data->as_array());
    }
    else {
      $this->result(1);
    }
  }

  public function action_list()
  {
    $page = max((int) Arr::get($_GET, 'page', 1), 1);
    $where = array('page'=>$page);
    $data = $this->model_company->get_list_front($this->city_id, $where);
    if ($data) {
      $this->result(0, $data->as_array());
    }
    else {
      $this->result(1);
    }
  }

  public function action_favorite()
  {
    $ids = Arr::get($_GET, 'fv', FALSE);
    if ($ids) {
      $ids = explode('|', $ids);
      $data = $this->model_company->get_list_favorite($this->city_id, $ids);
      if ($data) {
        $this->result(0, $data->as_array());
      }
      else {
        $this->result(1);
      }
    }
  }

  public function action_article()
  {
    $page = max((int) Arr::get($_GET, 'page', 1), 1);
    $this->model_article = Model::factory('Article');
    $this->model_category = Model::factory('Article_Category');
    $category_id = $this->core->company_article_id;
    $where['page'] = $page;
    $where['cat'] = $category_id;
    if ($article = $this->model_article->get_list_front($this->city_id, $where)) {
        $category = $this->model_category->get_list_pretty();
        $this->result(0, $article->as_array(), array('category'=>$category));
    }
  }



} // End API
