<?php defined('SYSPATH') or die('No direct script access.');

class Map_Baidu extends Map {

  protected $query;

  protected $geocoder_url = 'http://api.map.baidu.com/geocoder/v2/';

  public function __construct(array $config)
  {
    parent::__construct($config);

    $this->query = array(
                    'output'   => 'json',
                    'ak'       => $this->_config['map_ak'],
                  );
  }
  public function getgeo($location, $reverse=TRUE)
  {
    $data = $this->geocoder($location, $reverse);
    if ($data) {
      $this->_output['lat'] = $data->result->location->lat;
      $this->_output['lng'] = $data->result->location->lng;
      if (isset($data->result->formatted_address)) {
        $this->_output['address'] = $data->result->formatted_address;
        $this->_output['city'] = $data->result->addressComponent->city;
        $this->_output['district'] = $data->result->addressComponent->district;
        $this->_output['street'] = $data->result->addressComponent->street;
        $this->_output['street_number'] = $data->result->addressComponent->street_number;
        $this->_output['province'] = $data->result->addressComponent->province;
      }
      elseif (isset($data->result->location)) {
        $this->_output['confidence'] = $data->result->confidence;
        $this->_output['level'] = $data->result->level;
      }
      return $this->_output;
    }
    return NULL;
  }

  public function geocoder($geo, $reverse = FALSE) {
    if($reverse) {
      $query = array(
                'address' => $geo, 
              );
    }
    else {
      // 逆地理编码服务
      $query = array(
                'location' => $geo, 
              );

    }

    $query = URL::query(  $query + $this->query );

    ini_set('default_socket_timeout', 2);

    $data = file_get_contents($this->geocoder_url . $query);
    $data = json_decode($data);
    if ($data && isset($data->status) && $data->status == 0) {
      return $data;
    }
    return NULL;

  }


} // End Map
