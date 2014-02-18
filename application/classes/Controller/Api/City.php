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
    $id = $this->request->param('id');
    if(empty($id)) {
      $id = Arr::get($_GET, 'name', FALSE);
    }

    if ($id && ($city_id = $this->check_exist_city($id)))  {
      $this->body['error'] = 0;
      $this->body['data'] = $city_id;
      Cookie::set('city_id', $city_id);
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
      $pretty = array_flip($this->city_pretty);
      if (in_array($city, $this->city_pretty)) {
        return $pretty[$city];
      }
      $city = mb_substr($city, 0, -1);
      if (in_array($city, $this->city_pretty)) {
        return $pretty[$city];
      }
    }
    else {
      
    }
    return FALSE;
  }

} // End API
