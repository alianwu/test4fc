<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_House extends Controller_Api {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('House_New');
  }

  public function action_list()
  {
    $page = (int) Arr::get($_GET, 'page', 0);
    $cid  = (int) Arr::get($_GET, 'cid', 0);
    if ($cid <> 0 && $page <> 0) {
      $data = $this->model->get_house_front($cid);
      if ($data) {
        $this->result(0, $data->as_array());
      }
    }
  }

  public function action_search()
  {
    $data = Arr::extract($_GET, array('keyword', 'area', 'price', 'group', 'underground', 'page'));
    $data = $this->model->get_search_front($this->city_id, $data);
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
    $radius  = (int) Arr::get($_GET, 'radius', 1500000000);

    $data = $this->model->get_near_front($city_id, $lat, $lng, $radius, $page);
    $this->result(0);
    if ($data) {
      $this->result(NULL, $data->as_array());
    }
  }

} // End API
