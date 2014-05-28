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
  public $pjax = NULL;

  public $core = NULL;
  public $setting = NULL;
  public $var = NULL;

  public $user = NULL;
  public $city_pretty = NULL;
  public $city_cache = NULL;
  public $city_area = NULL;

  // default: Beijing
  public $city_id = 1;
  public $city_lng = '116.404';
  public $city_lat = '39.915';
  public $city_radius = 20000000;
  public $pagination = NULL;
  public $token = NULL;
  public $result = array('status'=>1, 'data'=>NULL);
  public $cache;
  public $session;
  public $model;
  public $model_city;
  public $model_config;

  public $us_name = 'accounts.user';
  public $us_name_m = 'accounts.manager';

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
    $this->model_city = Model::factory('City');
    $this->model_config = Model::factory('Config');
    $this->cache = Cache::instance();
    $this->session = Session::instance();
    $cache = $this->cache->get('core', NULL); 
    if (empty($cache) == TRUE) {
      $cache['city_pretty'] = $this->model_city->get_city_pretty(0, 1, TRUE, 1);
      $cache['city_cache'] = $this->model_city->get_city_pretty(NULL, NULL, TRUE, 1);
      $cache['config'] =  $this->model_config->get_all();
      $cache['setting'] = Kohana::$config->load('setting');
      $cache['pagination'] = Kohana::$config->load('pagination.default');
      $this->cache->set('core', $cache);
    }

    $this->city_pretty = $cache['city_pretty'];
    $this->city_cache = $cache['city_cache'];
    $this->var = $cache['config'];
    $this->core = (object) $this->var['core'];
    $this->setting = $cache['setting'];
    $this->pagination = $cache['pagination'];
    // session
    $this->user = $this->session->get($this->us_name);
    $this->manager = $this->session->get($this->us_name_m);
    $this->initialize_city();
    $this->token = Security::token();

    $cache_city_area = $this->cache->get('cache_city_area', NULL); 
    if ($cache_city_area == NULL || isset($_GET['city'])) {
      $cache_city_area = $this->model_city->get_city_pretty($this->city_id, 1, TRUE, 1);
      $this->cache->set('cache_city_area', $cache_city_area);
    }
    $this->city_area = $cache_city_area;

    // pjax
    $this->pjax = (bool) Arr::get($_SERVER, 'HTTP_X_PJAX', FALSE);
    if ($this->auto_render === TRUE)
    {
      // $this->response->headers('cache-control', 'max-age=3600');
      // Load the template
      $this->template = View::factory($this->template);
      $this->template->bind_global('core', $this->core);
      $this->template->bind_global('var', $this->var);
      $this->template->bind_global('setting', $this->setting);
      $user = '';
      if ($this->user) {
        $user = json_encode($this->user);
      }
      $this->template->bind_global('user', $user);
      $this->template->bind_global('city_pretty', $this->city_pretty);
      $this->template->bind_global('city_cache', $this->city_cache);
      $this->template->bind_global('city_area', $this->city_area);
    }
  }

  public function check_user()
  {
   return $this->user !== NULL; 
  }

  public function initialize_city($id = NULL, $geo = NULL)
  {
    $city_id = (int) Cookie::get('city_id');
    if ($id !== NULL) { 
      $this->city_id = $id;
      if (isset($geo['lat'])) {
        $geo['city_id'] =$this->city_id;
      }
      $this->session->set('geo', $geo);
    }
    elseif (isset($_GET['city']) 
              && isset($this->city_pretty[$_GET['city']])) {
      $this->city_id = $_GET['city'];
    }
    elseif ($city_id <> 0 
              && isset($this->city_pretty[$city_id])) {
      $this->city_id = $city_id;
    }
    elseif ($v = Arr::get($_SERVER, 'GEOIP_CITY') ) {
      $ret = $this->model_city->get_city_from_value(strtolower($v));
      if ($ret) {
        $this->city_id = $ret->get('cid');
        $this->city_lng = Arr::get($_SERVER, 'GEOIP_LONGITUDE');
        $this->city_lat = Arr::get($_SERVER, 'GEOIP_LATITUDE');
        return TRUE;
      }
    }
    else {
      // do nothing
    }

    $geo = $this->session->get('geo');
    if ($geo && isset($geo['city_id']) && $this->city_id == $geo['city_id']) {
        $this->city_lng = $geo['lng'];
        $this->city_lat = $geo['lat'];
    }
    elseif ($geo = $this->cache->get($cache_name = 'geo_city_id_'.$this->city_id, FALSE) ) {
      $this->city_lng = $geo['lng'];
      $this->city_lat = $geo['lat'];
    }
    else {
      // do nothing
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
      // do nothing
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

  // view
  public function view( & $view) 
  {
    if ($this->pjax === TRUE) {
      if ($view instanceof View) {
        $this->template = $view;
      }
      else {
        $this->template = View::factory($view);
      }
    }
    else {
      $this->template->view = $view;
    }
  }

  /**
   * Assigns the template [View] as the request response.
   */
  public function after()
  {
    if ($this->auto_render === TRUE) {
      if ($this->request->method() == 'POST') {
        $this->token = Security::token( TRUE );
      }
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
