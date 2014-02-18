<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_House extends Controller_Api {

  public function before()
  {
    parent::before();

    $this->model_house = Model::factory('House_New');
  }

  public function action_list()
  {
    $page = (int) Arr::get($_GET, 'page', 0);
    $cid  = (int) Arr::get($_GET, 'cid', 0);
    if ($cid <> 0 && $page <> 0) {
      $data = $this->model_house->get_house_front($cid);
      if ($data) {
        $this->body['data'] = $data->as_array();
      }
    }
  }

  public function action_near()
  {
    $zoom = (int) Arr::get($_GET, 'zoom', 9);
    $lat  = (int) Arr::get($_GET, 'lat', 0);
    $lng  = (int) Arr::get($_GET, 'lng', 0);
    $radius  = (int) Arr::get($_GET, 'radius', 1500);
    if ($zoom && $lat && $lng) { 
      if ( ($city_id = (int) Arr::get($_GET, 'city_id', 0)) == 0 ) {
        $city_id = $this->city_id;
      }

      $data = $this->model_house->get_near_front($city_id, $lat, $lng, $radius);
      if ($data) {
        $this->body['error'] = 0;
        $this->body['data'] = $data->as_array();
      }
    }
  }

} // End API
