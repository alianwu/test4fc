<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_City extends Controller_Api {

  public function action_get_city()
  {
    $cid = Arr::get($_GET, 'cid', 0);
    $type = Arr::get($_GET, 'type', 0);
    $data = $this->model_city->get_city_pretty($cid, $type);
    if ($data) {
      $this->result(0, $data);
    }
  }
  
  public function action_set_city()
  {
    $extra = array();
    $id = '';
    if(isset($_GET['name'])) {
      $id = Arr::get($_GET, 'name', '');
    }
    elseif( ($lat = Arr::get($_GET, 'lat', '') ) &&  ($lng = Arr::get($_GET, 'lng', '') )) {
      $extra['lat'] = $lat;
      $extra['lng'] = $lng;
      $id = $lat.','.$lng;
    }
    else {
      $id = $this->request->param('id');
    }

    if ($id && ($city_id = $this->check_exist_city($id)))  {
      $this->city_id = $city_id;
      $ret = $this->initialize_city($this->city_id, $extra);
      $this->result(0, $this->city_id);
    }
  }

  public function action_update_city()
  {
    $lat = Arr::get($_GET, 'lat', 0);
    $lng = Arr::get($_GET, 'lng', 0);
    if (is_numeric($lat) && is_numeric($lng) && $lat & $lng) {
      $this->initialize_city($this->city_id, array('lat'=>$lat, 'lng'=>$lng));
      $this->result(0);
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
    $name = UTF8::substr($name, 0, -1);
    if (in_array($name, $this->city_pretty)) {
      return $pretty[$name];
    }
    return FALSE;
  }

} // End API
