<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_City extends Controller_Api {

  public function action_get_city()
  {
    $cid = Arr::get($_GET, 'cid', 0);
    $type = Arr::get($_GET, 'type', 0);
    $this->body = $this->model_city->get_city_pretty($cid, $type);
  }

  public function action_set_city()
  {
    $id = '';
    if(isset($_GET['name'])) {
      $id = Arr::get($_GET, 'name', '');
    }
    elseif(isset($_GET['localtion'])) {
      $id = Arr::get($_GET, 'localtion', '');
    }
    else {
      $id = $this->request->param('id');
    }

    if ($id && ($city_id = $this->check_exist_city($id)))  {
      $this->body['error'] = 0;
      $this->body['data'] = $city_id;
      $this->city_id = $city_id;
      $ret = $this->initialize_city($this->city_id);
      if ($ret == FALSE) {
        $this->body['data'] = 0;
      }
    }
  }
 
  protected function check_exist_city($city)
  {
    if(is_numeric($city)) {
     if (array_key_exists((int)$city, $this->city_pretty)) {
      // return $this->city_pretty[$city];
       return $city;
     }
    }
    elseif (is_string($city)) {
      $_city = $this->check_exist_city_name($city);
      if ($_city) {
        return $_city;
      }

      $geo = Map::instance()->geocoder($city);
      if($geo->status == 0 && isset($geo->result->addressComponent->city)) {
        $city = $geo->result->addressComponent->city;
      } 
      $_city = $this->check_exist_city_name($city);
      if ($_city) {
        return $_city;
      }
    }
    else {
      
    }
    return FALSE;
  }

  protected function check_exist_city_name($name)
  {
    $pretty = array_flip($this->city_pretty);
    if (in_array($name, $this->city_pretty)) {
      return $pretty[$name];
    }
    $name = mb_substr($name, 0, -1);
    if (in_array($name, $this->city_pretty)) {
      return $pretty[$name];
    }
    return FALSE;
  }

} // End API
