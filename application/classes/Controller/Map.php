<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Map extends Controller_Template {

  public $auto_render = FALSE;

  public $location;
  public $address;
  public $output = 'json';
  public $lat;
  public $lng;
  public $query;
  protected $baidu_geocoder_url = 'http://api.map.baidu.com/geocoder/v2/';

  public function before()
  {
    parent::before();

    $this->location = Arr::get($_GET, 'location', '');
    $this->address = Arr::get($_GET, 'address', '');
    $this->output =  Arr::get($_GET, 'output', 'json');

    $this->query = array(
                    'output'   => $this->output,
                    'ak'       => $this->core->bd_map_ak
                  );
  }
  public function action_getaddress()
  {
    $data = $this->geocoder();
    $body = array('error'=>1, 'info'=>'');
    if ($data) {
      $body['error'] = 0;
      $body['info'] = $data->result->formatted_address;
    }
    $this->response->body(json_encode($body))->headers('Content-Type', 'application/json');
  }

  public function action_get_city()
  {
    $data = $this->geocoder();
    $body = array('error'=>1, 'info'=>'');
    if ($data) {
      $body['error'] = 0;
      $body['info'] = $data->result->addressComponent->city;
      $body['address'] = $data->result->formatted_address;
    }
    $this->response->body(json_encode($body))->headers('Content-Type', 'application/json');
  }

  public function geocoder($r = TRUE, $param = NULL) {
    if ($param !== NULL) {
      foreach($param as $k => $v) {
        $this->{$key} = $v;
      }
    }
    if($r) {
      $query = array(
                'location' => $this->location, 
              );
    }
    else {
      // 逆地理编码服务
      $query = array(
                'address' => $this->address, 
              );

    }
    $query = URL::query(  $query + $this->query );

    ini_set('default_socket_timeout', 2);

    $data = file_get_contents($this->baidu_geocoder_url . $query);
    $data = json_decode($data);
    if ($data && isset($data->status) && $data->status == 0) {
      return $data;
    }
    return FALSE;

  }


} // End Map
