<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract controller class for automatic templating.
 *
 * @package    Kohana
 * @category   Controller
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Controller_Template extends Controller {

  /**
   * @var  View  page template
   */
  public $template = 'template';

  public $core = NULL;
  public $setting = NULL;

  public $user = NULL;
  public $city_pretty = NULL;
  public $city_cache = NULL;
  public $city_area = NULL;
  public $city_id = 1;
  public $city_lng = '116.404';
  public $city_lat = '39.915';
  public $city_radius = 2000;
  public $pagination = NULL;
  public $token = '';
  public $result = array('status'=>1, 'data'=>NULL);
  public $model;

  /**
   * @var  boolean  auto render template
   **/
  public $auto_render = TRUE;
  
  /**
   * Loads the template [View] object.
   */
  public function before()
  {
    parent::before();

    $http_x_pjax =  (bool) Arr::get($_SERVER, 'HTTP_X_PJAX', FALSE);
    if ($http_x_pjax) {
      $this->auto_render = FALSE;
    }
    $this->model_city = Model::factory('City');
    $this->city_pretty = $this->model_city->get_city_pretty();

    $this->user = $this->get_user('member.user');

    $this->initialize_city();

    $this->city_area = $this->model_city->get_city_pretty($this->city_id);
    $this->city_cache = $this->model_city->get_city_cache();
    $this->core = Kohana::$config->load('core');
    $this->setting = Kohana::$config->load('setting');
    $this->map = Kohana::$config->load('map.baidu');
    $this->token = Security::token();

    $this->pagination = Kohana::$config->load('pagination.default');

    if ($this->auto_render === TRUE)
    {
      // Load the template
      $this->template = View::factory($this->template);
      $map = Kohana::$config->load('map.baidu');
      $this->template->bind_global('map', $map);
      $this->template->bind_global('core', $this->core);
      $this->template->bind_global('setting', $this->setting);
      $this->template->bind_global('map', $this->map);
      $this->template->bind_global('user', $this->user);
      $this->template->bind_global('city_pretty', $this->city_pretty);
      $this->template->bind_global('city_cache', $this->city_cache);
      $this->template->bind_global('city_area', $this->city_area);
    }
  }

  public function check_user()
  {
   return $this->user !== NULL; 
  }

  public function get_user($session_name = 'user', $sessionid=NULL)
  {
    $user = Session::instance()->get($session_name, NULL);
    return $user;
  }

  public function initialize_city($id = NULL, $geo = NULL)
  {
    $city_id = (int) Cookie::get('city_id');
    if ($id !== NULL) { 
      $this->city_id = $id;
      if (isset($geo['lat'])) {
        $geo['city_id'] =$this->city_id;
      }
      Session::instance()->set('geo', $geo);
    }
    elseif  ($city_id <> 0 
        && isset($this->city_pretty[$city_id])) {
      $this->city_id = $city_id;
    }
    elseif ( $v = Arr::get($_SERVER, 'GEOIP_CITY') ) {
      $ret = $this->model_city->get_city_from_value(strtolower($v));
      if ($ret) {
        $this->city_id = $ret->get('cid');
        $this->city_lng = Arr::get($_SERVER, 'GEOIP_LONGITUDE');
        $this->city_lat = Arr::get($_SERVER, 'GEOIP_LATITUDE');
        return TRUE;
      }
    }
    elseif(FALSE) { 
      // from baidu map
    }
    else {

    }

    $geo = Session::instance()->get('geo');
    if ($geo && isset($geo['city_id']) && $this->city_id == $geo['city_id']) {
        $this->city_lng = $geo['lng'];
        $this->city_lat = $geo['lat'];
    }
    elseif ($geo = Cache::instance()->get($cache_name = 'geo_city_id_'.$this->city_id, FALSE) ) {
      $this->city_lng = $geo['lng'];
      $this->city_lat = $geo['lat'];
    }
    elseif ($geo = Map::instance()->geocoder($this->city_pretty[$this->city_id], TRUE)) {
      if($geo && isset($geo->result->location->lng) && $geo->result->location->lng <> '')
      {
        $this->city_lng = $geo->result->location->lng;
        $this->city_lat = $geo->result->location->lat;
      } 
      else {
        $this->city_lng = Arr::get($_SERVER, 'GEOIP_LONGITUDE');
        $this->city_lat = Arr::get($_SERVER, 'GEOIP_LATITUDE');
      }
      Cache::instance()->set($cache_name, array('lng'=>$this->city_lng, 'lat'=>$this->city_lat));
    }

    Cookie::set('city_id', $this->city_id);
    return TRUE;
  }

  public function result($status = NULL, $data = NULL, $extra = NULL)
  {
    if ($status === FALSE) {
      $this->result['status'] = 1;
    }
    elseif ($status === TRUE) {
      $this->result['status'] = 0;
    }
    elseif ($status === NULL) {
      // pass
    }
    else {
      $this->result['status'] = (int) $status;
    }
    $this->result['data']  = $data;
    if (is_array($extra)) {
      $this->result = array_merge($this->result, $extra); 
    }

    if ($this->auto_render == TRUE) {
      $this->template->bind_global('result', $this->result);
    }
  }


  /**
   * Assigns the template [View] as the request response.
   */
  public function after()
  {
    if ($this->request->method() == 'POST') {
      $this->token = Security::token( TRUE );
    }

    if ($this->auto_render === TRUE) {
      $this->template->set_global('city_id', $this->city_id);
      $this->template->set_global('city_lng', $this->city_lng);
      $this->template->set_global('city_lat', $this->city_lat);
      $this->template->set_global('token', $this->token);
      $this->template->set_global('pagination', $this->pagination);
      $this->response->body($this->template->render());
    }

    parent::after();
  }

}
